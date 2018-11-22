<?php
/**
 * Created by PhpStorm.
 * User: weinan
 * Date: 2018/11/18
 * Time: 16:10.
 */

namespace RedisLock;

use Predis\Client;

class Lock implements RedisLockInterface
{
    private $redisClient;

    private $randomString;

    private $ttl = 10000;

    public function __construct(Client $client)
    {
        $this->redisClient = $client;
    }

    private function randomString()
    {
        $byte = openssl_random_pseudo_bytes(32);
        $this->randomString = date('YmdHis').md5($byte);
    }

    public function setDefaultTTL($ttl)
    {
        $this->ttl = $ttl;

        return true;
    }

    public function getDefaultTTL()
    {
        return $this->ttl;
    }

    public function lock($key, $ttl = null)
    {
        $expire = is_null($ttl) ? $this->ttl : $ttl;
        $this->randomString();

        return $this->redisClient->executeRaw(['SET', $key, $this->randomString, 'NX', 'PX', $expire]);
    }

    public function release($key)
    {
        $luaScript = <<<'LUA'
if redis.call("get",KEYS[1]) == ARGV[1] then
    return redis.call("del",KEYS[1])
else
    return 0
end
LUA;

        return $this->redisClient->eval($luaScript, 1, $key, $this->randomString);
    }

    public function get($key)
    {
        return $this->redisClient->get($key);
    }

    /**
     * @param $key
     * @param $sleepTime
     *
     * @return \Generator
     */
    private function retry($key, $sleepTime)
    {
        while (true) {
            yield $this->redisClient->get($key);
            usleep($sleepTime);
        }
    }

    /**
     * @return mixed
     */
    public function getRandomString()
    {
        return $this->randomString;
    }

    /**
     * @param $key
     * @param int $retry
     * @param int $sleep
     *
     * @return mixed|null
     */
    public function isLockReleased($key, $retry = 3, $sleep = 200000)
    {
        $result = false;

        // 如果返回null说明锁已释放，返回true，否则返回false。
        foreach ($this->retry($key, $sleep) as $data) {
            $retry -= 1;
            if (is_null($data)) {
                $result = true;
                break;
            }
            if ($retry <= 0) {
                break;
            }
        }

        return $result;
    }
}
