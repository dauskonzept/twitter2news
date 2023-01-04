<?php

declare(strict_types=1);

namespace DSKZPT\Twitter2News\Event\NewsTweet;

use DSKZPT\Twitter2News\Domain\Model\NewsTweet;

class PrePersistEvent
{
    private NewsTweet $newsTweet;

    private \stdClass $tweet;

    /**
     * Used to control if given Tweet should be imported/persisted
     */
    private bool $persistTweet = true;

    public function __construct(NewsTweet $newsTweet, \stdClass $tweet)
    {
        $this->newsTweet = $newsTweet;
        $this->tweet = $tweet;
    }

    public function getNewsTweet(): NewsTweet
    {
        return $this->newsTweet;
    }

    public function setNewsTweet(NewsTweet $newsTweet): void
    {
        $this->newsTweet = $newsTweet;
    }

    public function getTweet(): \stdClass
    {
        return $this->tweet;
    }

    public function persistTweet(): bool
    {
        return $this->persistTweet;
    }

    public function doNotPersistTweet(): self
    {
        $this->persistTweet = false;

        return $this;
    }
}
