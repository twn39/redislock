<?php
/**
 * Created by PhpStorm.
 * User: weinan
 * Date: 2018/11/18
 * Time: 16:07
 */
namespace RedisLock;

interface RedisLockInterface
{

    public function lock($key, $ttl);

    public function release($key);

    public function isLockReleased($key, $retry, $sleep);
}