## Redis简单分布式锁

[![Build Status](https://travis-ci.org/twn39/redislock.svg?branch=master)](https://travis-ci.org/twn39/redislock)
[![Maintainability](https://api.codeclimate.com/v1/badges/d9611cb59629a002c200/maintainability)](https://codeclimate.com/github/twn39/redislock/maintainability)
[![StyleCI](https://github.styleci.io/repos/158085465/shield?branch=master)](https://github.styleci.io/repos/158085465)

基于redis的简单分布式锁，只支持单个redis实例，不支持redis集群

## 安装

    composer require monster/redislock
    
## 使用

```php
$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '192.168.20.10',
    'port'   => 6379,
]);

$redisLock = new RedisLock\Lock($client);

$result = $redisLock->lock('user:1');
var_dump($result);

$result = $redisLock->isLockReleased('user:1');

$result = $redisLock->release('user:1');
var_dump($result);
```

## 方法

```php
<?php
namespace RedisLock;

interface RedisLockInterface
{
    public function lock($key, $ttl);

    public function release($key);

    public function isLockReleased($key, $retry, $sleep);
}
```

