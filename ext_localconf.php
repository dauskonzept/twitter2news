<?php

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News']['twitter2news'] = 'twitter2news';

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
    ->registerImplementation(
        \GeorgRinger\News\Domain\Model\News::class,
        \DSKZPT\Twitter2News\Domain\Model\NewsTweet::class
    );
