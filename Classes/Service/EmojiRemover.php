<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "twitter2news" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace SvenPetersen\Twitter2News\Service;

class EmojiRemover
{
    public static function filter(string $string = ''): string
    {
        return preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $string);
    }
}
