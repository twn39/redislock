## Redis简单分布式锁

[![Build Status](https://travis-ci.org/twn39/redislock.svg?branch=master)](https://travis-ci.org/twn39/redislock)

基于redis的简单分布式锁，只支持单个redis实例，不支持redis集群

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

