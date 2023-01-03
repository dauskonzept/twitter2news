[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)
[![TYPO3 10](https://img.shields.io/badge/TYPO3-10-orange.svg)](https://get.typo3.org/version/10)
[![TYPO3 11](https://img.shields.io/badge/TYPO3-11-orange.svg)](https://get.typo3.org/version/11)
[![Latest Stable Version](http://poser.pugx.org/svenpetersen/twitter2news/v)](https://packagist.org/packages/svenpetersen/twitter2news)
[![Total Downloads](http://poser.pugx.org/svenpetersen/twitter2news/downloads)](https://packagist.org/packages/svenpetersen/twitter2news)
[![Latest Unstable Version](http://poser.pugx.org/svenpetersen/twitter2news/v/unstable)](https://packagist.org/packages/svenpetersen/twitter2news)
[![License](http://poser.pugx.org/svenpetersen/twitter2news/license)](https://packagist.org/packages/svenpetersen/twitter2news)
[![PHP Version Require](http://poser.pugx.org/svenpetersen/twitter2news/require/php)](https://packagist.org/packages/svenpetersen/twitter2news)

TYPO3 Extension "twitter2news"
=================================

## What does it do?

Imports tweets via the official Twitter API
as [EXT:news](https://github.com/georgringer/news)
"News" entities.

**Summary of features**

* Integrates with [EXT:news](https://github.com/georgringer/news) to import
  tweets as News entities
* Provides command to regularly import new/update already imported tweets
* Adds a new subtype for EXT:news: "Tweet"

## Installation

The recommended way to install the extension is by
using [Composer](https://getcomposer.org/). In your Composer based TYPO3 project
root, just run:
<pre>composer require svenpetersen/twitter2news</pre>

## Setup

1. Get your twitter API access tokens by following
   the [official documentation](https://developer.twitter.com/en/docs/twitter-api/getting-started/about-twitter-api)
2. Enter your API access tokens in the Extension configuration/settings.
3. Run the provided command to import tweets: <pre>twitter2news:import-tweets
   {username} {storagePid} [limit|25|max:100]</pre>

__Recommended__:

Setup a cronjob/scheduler task to regularly import new tweets.

## Compatibility

| Version | TYPO3       | News       | PHP        | Support/Development                  |
|---------|-------------|------------|------------|--------------------------------------|
| 1.x     | 10.4 - 11.5 | 9.0 - 10.x | 7.4 - 8.0️ | Features, Bugfixes, Security Updates |

## Funtionalities

### Automatic import of posts

This extension comes with a command to import tweets of a given twitter handle.
It is recommended to set this command up to run regularly - e.g. once a day.

<pre>twitter2news:import-tweets {username} {storagePid} [limit|25|max:100]</pre>

__Arguments:__

| Name       | Description                                                                      |
|------------|----------------------------------------------------------------------------------|
| username   | The users twitter handle to import tweets from                                   |
| storagePid | The PID to save the imported tweets                                              |
| limit      | The maximum number of latest tweets to import (Optional. Default: 25 / max: 100) |

### Local path to save downloaded files

By default all images/videos in imported posts are saved in <code>
/public/fileadmin/twitter2news</code>
You can change this path via the Extensions settings <code>
local_file_storage_path</code> option.

## Known issues / limitations

* The max number of Tweets to import is currently limited to 100 ("the last 100
  tweets of a user"). That is the Twitter APIs limit for one tweets in one
  response. This limit can be fixed by making use of the APIs pagination
  functionality.

## Contributing

Please refer to the [contributing](CONTRIBUTING.md) document included in this
repository.

## Testing

This Extension comes with a testsuite for coding styles and unit/functional
tests. To run the tests simply use the provided composer script:

<pre>composer ci:test</pre>
