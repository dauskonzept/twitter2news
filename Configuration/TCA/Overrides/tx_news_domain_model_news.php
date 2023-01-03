<?php

defined('TYPO3') or die();

$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['1672691843'] =
    ['Tweet', 1672691843];

$GLOBALS['TCA']['tx_news_domain_model_news']['types']['1672691843'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

$fields = [
    'tweet_id' => [
        'exclude' => 1,
        'label' => 'Tweet ID',
        'config' => [
            'type' => 'input',
            'size' => 30,
        ],
    ],
    'tweeted_by' => [
        'exclude' => 1,
        'label' => 'Tweeted by',
        'config' => [
            'type' => 'input',
            'size' => 30,
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tx_news_domain_model_news',
    'tx_twitter2news_fields',
    'tweet_id, tweeted_by'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Twitter,--palette--;LLL:EXT:twitter2news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.palette.tx_twitter2news_fields;tx_twitter2news_fields',
    '1672691843',
    ''
);
