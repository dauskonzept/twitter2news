<?php

declare(strict_types=1);

namespace DSKZPT\Twitter2News\Event\NewsTweet;

class ExcludedRetweetEvent
{
    private \stdClass $tweet;

    public function __construct(\stdClass $tweet)
    {
        $this->tweet = $tweet;
    }

    public function getTweet(): \stdClass
    {
        return $this->tweet;
    }
}
