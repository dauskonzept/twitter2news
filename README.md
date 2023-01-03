TYPO3 Extension "twitter2news"
=================================

## What does it do?

Imports tweets as [georgringer/news](https://github.com/georgringer/news)
articles.

**Summary of features**

* todo

## Installation

The recommended way to install the extension is by
using [Composer](https://getcomposer.org/). In your Composer based TYPO3 project
root, just run:
<pre>composer require svenpetersen/twitter2news</pre>

## Setup

1. Get your twitter API access tokens by following
   the [official documentation](https://developer.twitter.com/en/docs/twitter-api/getting-started/about-twitter-api)
2. Set your API access tokens in the Extension configuration/settings.
3. Run the provided command <code>twitter2news:
   import-tweets <username> <storagePid> [limit|25|max:100]</code> to import
   tweets.

## Compatibility

| Version | TYPO3       | PHP        | Support/Development                  |
|---------|-------------|------------|--------------------------------------|
| 2.x     | 12.0        | 8.1 - 8.02 | In development                       |
| 1.x     | 10.4 - 11.5 | 7.4 - 8.0️ | Features, Bugfixes, Security Updates |

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
| limit      | The maximum number of latest tweets to import (Optional. Default: 25 / Max: 100) |

## Contributing

Please refer to the [contributing](CONTRIBUTING.md) document included in this
repository.

## Testing

This Extension comes with a testsuite for coding styles and unit/functional
tests. To run the tests simply use the provided composer script:

<pre>composer ci:test</pre>
