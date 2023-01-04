<?php

declare(strict_types=1);

namespace DSKZPT\Twitter2News\Domain\Model;

use GeorgRinger\News as GeorgRingerNews;

class NewsTweet extends GeorgRingerNews\Domain\Model\News
{
    /**
     * @var int
     */
    protected $_languageUid = -1;

    protected string $tweetId = '';

    protected string $tweetedBy = '';

    public function getTweetId(): string
    {
        return $this->tweetId;
    }

    public function setTweetId(string $tweetId): self
    {
        $this->tweetId = $tweetId;

        return $this;
    }

    public function getTweetedBy(): string
    {
        return $this->tweetedBy;
    }

    public function setTweetedBy(string $tweetedBy): self
    {
        $this->tweetedBy = $tweetedBy;

        return $this;
    }
}
