<?php

declare(strict_types=1);

namespace SvenPetersen\Twitter2News\Event\NewsTweet;

use SvenPetersen\Twitter2News\Domain\Model\NewsTweet;

class PreDownloadMediaEvent
{
    private NewsTweet $newsTweet;

    private \stdClass $media;

    public function __construct(NewsTweet $newsTweet, \stdClass $media) {
        $this->newsTweet = $newsTweet;
        $this->media = $media;
    }

    public function getNewsTweet(): NewsTweet
    {
        return $this->newsTweet;
    }

    public function setNewsTweet(NewsTweet $newsTweet): void
    {
        $this->newsTweet = $newsTweet;
    }

    public function getMedia(): \stdClass
    {
        return $this->media;
    }
}
