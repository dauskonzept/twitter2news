<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "twitter2news" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace SvenPetersen\Twitter2News\Command;

use Psr\EventDispatcher\EventDispatcherInterface;
use SvenPetersen\Twitter2News\Client\TwitterApiClient;
use SvenPetersen\Twitter2News\Domain\Model\NewsTweet;
use SvenPetersen\Twitter2News\Domain\Repository\NewsTweetRepository;
use SvenPetersen\Twitter2News\Event\NewsTweet\NotPersistedEvent;
use SvenPetersen\Twitter2News\Event\NewsTweet\PostDownloadMediaEvent;
use SvenPetersen\Twitter2News\Event\NewsTweet\PostPersistEvent;
use SvenPetersen\Twitter2News\Event\NewsTweet\PreDownloadMediaEvent;
use SvenPetersen\Twitter2News\Event\NewsTweet\PrePersistEvent;
use SvenPetersen\Twitter2News\Service\EmojiRemover;
use SvenPetersen\Twitter2News\Service\SlugService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

class ImportTweetsCommand extends Command
{
    private NewsTweetRepository $newsRepository;

    private PersistenceManagerInterface $persistenceManager;

    private EventDispatcherInterface $eventDispatcher;

    private string $username = '';

    /**
     * @var \stdClass[]
     */
    private array $mediaData = [];

    /**
     * @var array<string, string>
     */
    private array $extConf;

    public function __construct(
        NewsTweetRepository $newsRepository,
        PersistenceManagerInterface $persistenceManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->newsRepository = $newsRepository;
        $this->persistenceManager = $persistenceManager;
        $this->eventDispatcher = $eventDispatcher;

        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->extConf = $extensionConfiguration->get('twitter2news');

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Imports tweets as ETX:news articles.')
            ->addArgument('username', InputArgument::REQUIRED, 'The Twitter username to import tweets from')
            ->addArgument('storagePid', InputArgument::REQUIRED, 'The PID where to save the news records')
            ->addArgument('limit', InputArgument::OPTIONAL, 'The maximum number of tweets to import (max: 100)', 25);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->username = $input->getArgument('username');
        $limit = (int)$input->getArgument('limit');
        $storagePid = $input->getArgument('storagePid');

        if (is_numeric($storagePid) === false) {
            throw new \InvalidArgumentException(sprintf('The StoragePid argument must be numeric. "%s" given.', $storagePid));
        }

        $apiClient = new TwitterApiClient();
        $result = $apiClient->getLatestTweets($this->username, $limit);

        if (property_exists($result, 'data') === false) {
            return Command::FAILURE;
        }

        $tweets = $result->data;

        // Prepare includes.media array
        if (property_exists($result, 'includes')) {
            $this->mediaData = $this->prepareMediaData($result->includes->media);
        }

        foreach ($tweets as $tweet) {
            $this->processTweet($tweet, (int)$storagePid);
        }

        SlugService::populateEmptySlugsInCustomTable('tx_news_domain_model_news', 'path_segment');

        return Command::SUCCESS;
    }

    private function processTweet(\stdClass $tweet, int $storagePid): NewsTweet
    {
        $newsTweet = $this->newsRepository->findOneByTweetId($tweet->id) ?? new NewsTweet();

        $filteredText = EmojiRemover::filter($tweet->text);

        $newsTweet->setTitle(substr($filteredText, 0, 255));
        $newsTweet->setBodytext($filteredText);
        $newsTweet->setTeaser($filteredText);
        $newsTweet->setTweetId($tweet->id);
        $newsTweet->setTweetedBy($this->username);
        $newsTweet->setPid($storagePid);
        $newsTweet->setDatetime(new \DateTime($tweet->created_at));

        /** @var PrePersistEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new PrePersistEvent($newsTweet, $tweet)
        );

        if ($event->persistTweet() === false) {
            $this->eventDispatcher->dispatch(new NotPersistedEvent($newsTweet, $tweet));
            
            return $newsTweet;
        }

        $newsTweet = $event->getNewsTweet();
        $isAlreadyImportedTweet = $newsTweet->getUid() !== null;

        $this->newsRepository->add($newsTweet);
        $this->persistenceManager->persistAll();

        // Don't download tweets media again
        if ($isAlreadyImportedTweet === true) {
            return $newsTweet;
        }

        if (property_exists($tweet, 'attachments')) {
            if (count($tweet->attachments->media_keys) > 0) {
                $mediaKeys = $tweet->attachments->media_keys;

                $this->processMedia($newsTweet, $mediaKeys);
            }
        }

        /** @var PostPersistEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new PostPersistEvent($newsTweet, $tweet)
        );

        return $event->getNewsTweet();
    }

    /**
     * @param array<string, \stdClass[]> $mediaIncludes
     *
     * @return array<string, \stdClass>
     */
    private function prepareMediaData(array $mediaIncludes): array
    {
        $return = [];

        /** @var \stdClass $mediaData */
        foreach ($mediaIncludes as $mediaData) {
            $return[(string)$mediaData->media_key] = $mediaData;
        }

        return $return;
    }

    /**
     * @param string[] $mediaKeys
     */
    private function processMedia(NewsTweet $newsTweet, array $mediaKeys): NewsTweet
    {
        foreach ($mediaKeys as $mediaKey) {
            if (!array_key_exists($mediaKey, $this->mediaData)) {
                return $newsTweet;
            }

            $mediaData = $this->mediaData[$mediaKey];

            /** @var PreDownloadMediaEvent $event */
            $event = $this->eventDispatcher->dispatch(
                new PreDownloadMediaEvent($newsTweet, $mediaData)
            );

            $newsTweet = $event->getNewsTweet();

            $mediaUrl = $mediaData->preview_image_url ?? $mediaData->url;

            $fileExtension = array_reverse(explode('.', $mediaUrl))[0];
            $file = $this->downloadFile($mediaUrl, $fileExtension);

            /** @var PostDownloadMediaEvent $event */
            $event = $this->eventDispatcher->dispatch(
                new PostDownloadMediaEvent($newsTweet, $file)
            );

            $newsTweet = $event->getNewsTweet();
            $file = $event->getFile();

            $this->addToFal($newsTweet, $file, 'tx_news_domain_model_news', 'fal_media');
        }

        return $newsTweet;
    }

    private function downloadFile(string $fileUrl, string $fileExtension): File
    {
        $relativeFilePath = $this->extConf['local_file_storage_path'];
        $directory = sprintf('%s%s', Environment::getProjectPath(), $relativeFilePath);
        GeneralUtility::mkdir_deep($directory);

        $directory = str_replace('1:', 'uploads', $directory);
        $filePath = sprintf('%s/%s.%s', $directory, md5($fileUrl), $fileExtension);

        $data = file_get_contents($fileUrl);
        file_put_contents($filePath, $data);

        /** @var ResourceFactory $resourceFactory */
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);

        /** @var File $file */
        $file = $resourceFactory->retrieveFileOrFolderObject($filePath);

        return $file;
    }

    private function addToFal(
        NewsTweet $newElement,
        File $file,
        string $tablename,
        string $fieldname
    ): void {
        $fields = [
            'pid' => $newElement->getPid(),
            'uid_local' => $file->getUid(),
            'uid_foreign' => $newElement->getUid(),
            'tablenames' => $tablename,
            'table_local' => 'sys_file',
            'fieldname' => $fieldname,
            'l10n_diffsource' => '',
            'sorting_foreign' => $file->getUid(),
            'tstamp' => time(),
            'crdate' => time(),
        ];

        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        $databaseConn = $connectionPool->getConnectionForTable('tx_news_domain_model_news');
        $databaseConn->insert('sys_file_reference', $fields);
    }
}
