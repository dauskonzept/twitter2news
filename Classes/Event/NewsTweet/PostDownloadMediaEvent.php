<?php

declare(strict_types=1);

namespace DSKZPT\Twitter2News\Event\NewsTweet;

use DSKZPT\Twitter2News\Domain\Model\NewsTweet;
use TYPO3\CMS\Core\Resource\File;

class PostDownloadMediaEvent
{
    private NewsTweet $newsTweet;

    private File $file;

    public function __construct(NewsTweet $newsTweet, File $file)
    {
        $this->newsTweet = $newsTweet;
        $this->file = $file;
    }

    public function getNewsTweet(): NewsTweet
    {
        return $this->newsTweet;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
