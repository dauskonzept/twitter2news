<?php

declare(strict_types=1);

namespace SvenPetersen\Twitter2News\Domain\Repository;

use GeorgRinger\News as GeorgRingerNews;
use SvenPetersen\Twitter2News\Domain\Model\NewsTweet;

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
