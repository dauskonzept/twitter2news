<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "twitter2news" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace SvenPetersen\Twitter2News\Client;

use Coderjerk\BirdElephant\BirdElephant;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TwitterApiClient
{
    private BirdElephant $twitter;

    public function __construct()
    {
        $this->twitter = $this->configureClient();
    }

    private function configureClient(): BirdElephant
    {
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('twitter2news');

        $credentials = [
            'consumer_key' => $extConf['consumer_key'],
            'consumer_secret' => $extConf['consumer_secret'],
            'bearer_token' => $extConf['bearer_token'],
            'token_identifier' => $extConf['token_identifier'],
            'token_secret' => $extConf['token_secret'],
        ];

        return new BirdElephant($credentials);
    }

    public function getLatestTweets(string $username, int $limit = 25): object
    {
        return $this->twitter->user($username)->tweets([
            'max_results' => $limit,
            'tweet.fields' => 'created_at,attachments',
            'expansions' => 'attachments.media_keys',
            'media.fields' => 'url,preview_image_url,media_key,width,height,alt_text',
        ]);
    }
}
