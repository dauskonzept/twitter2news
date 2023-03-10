<?php

declare(strict_types=1);

namespace DSKZPT\Twitter2News\Domain\Repository;

use DSKZPT\Twitter2News\Domain\Model\NewsTweet;
use GeorgRinger\News as GeorgRingerNews;

class NewsTweetRepository extends GeorgRingerNews\Domain\Repository\NewsRepository
{
    public function findOneByTweetId(string $tweetId): ?NewsTweet
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching($query->equals('tweetId', $tweetId));

        /** @var NewsTweet|null $result */
        $result = $query
            ->setLimit(1)
            ->execute()
            ->getFirst();

        return $result;
    }
}
