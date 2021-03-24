# Cache [![Build Status](https://travis-ci.com/alexdodonov/mezon-cache.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-cache) [![codecov](https://codecov.io/gh/alexdodonov/mezon-cache/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-cache)
## Intro

[Mezon](https://github.com/alexdodonov/mezon) provides simple cache class. Wich will help you to test how caching will affect performance of your code. If the performance will be boosted, then you can implement more complex solutions for caching.

## Installation

Just print in console

```
composer require mezon/cache
```

And that's all )

## How to use?

First of all we need to create single instance of cache ("singleton" pattern is used):

```php
$cache = Cache::getInstance();
```

Then we can add data to cache:

```php
$cache->set('key', 'data to be stored');
```

After all data was added you need to store it to be able using it within other requests.

```php
$cache->flush();
```

And then you can check if the key exists:

```php
var_dump($cache->exists('key')); // bool(true)
var_dump($cache->exists('unexisting-key')); // bool(false)
```

And then you can fetch data by it's key:

```php
var_dump($cache->get('key')); // string('data to be stored')
```

That's all for now )