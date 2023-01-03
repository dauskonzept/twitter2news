<?php

declare(strict_types=1);

return [
    \GeorgRinger\News\Domain\Model\News::class => [
        'subclasses' => [
            1672691843 => \SvenPetersen\Twitter2News\Domain\Model\NewsTweet::class,
        ],
    ],
    \SvenPetersen\Twitter2News\Domain\Model\NewsTweet::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 1672691843,
    ],
];
