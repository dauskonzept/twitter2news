<?php

declare(strict_types=1);

namespace DSKZPT\Twitter2News\Event\NewsTweet;

use DSKZPT\Twitter2News\Domain\Model\NewsTweet;

class NotPersistedEvent
{
    private NewsTweet $newsTweet;

    private \stdClass $tweet;

    public function __construct(NewsTweet $newsTweet, \stdClass $tweet)
    {
        $this->newsTweet = $newsTweet;
        $this->tweet = $tweet;
    }

    public function getNewsTweet(): NewsTweet
    {
        return $this->newsTweet;
    }

    public function getTweet(): \stdClass
    {
        return $this->tweet;
    }
}
