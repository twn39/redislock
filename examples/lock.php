<?php
/**
 * Created by PhpStorm.
 * User: weinan
 * Date: 2018/11/18
 * Time: 16:36
 */
require __DIR__.'/../vendor/autoload.php';

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '192.168.20.10',
    'port'   => 6379,
]);

$redisLock = new RedisLock\Lock($client);

$result = $redisLock->lock('user:1');
var_dump($result);

$result = $redisLock->getRetry('user:1');

$result = $redisLock->release('user:1');
var_dump($result);


echo 'success';