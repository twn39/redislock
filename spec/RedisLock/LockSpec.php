<?php

namespace spec\RedisLock;

use Predis\Client;
use RedisLock\Lock;
use PhpSpec\ObjectBehavior;
use RedisLock\RedisLockInterface;

class LockSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Client());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Lock::class);
        $this->shouldImplement(RedisLockInterface::class);
    }

    function it_can_set_default_ttl()
    {
        $this->setDefaultTTL(3000)->shouldReturn(true);
    }

    function it_can_get_default_ttl()
    {
        $this->setDefaultTTL(3000)->shouldReturn(true);
        $this->getDefaultTTL()->shouldReturn(3000);
    }

    function it_lock_key()
    {
        $this->lock('order:123', 1000)->shouldReturn('OK');
    }

    function it_get_key()
    {
        $this->lock('order:1', 1000);
        $this->get('order:1')->shouldBeString();
    }

    function it_release_lock()
    {
        $this->lock('order:30', 1000);
        $randString = $this->getRandomString();
        $this->get('order:30')->shouldBe($randString);
        $this->release('order:30');
        $this->get('order:30')->shouldReturn(null);
    }

    function it_is_lock_released()
    {
        $this->isLockReleased('order:3')->shouldBe(true);
        $this->lock('order:5');
        $this->isLockReleased('order:5')->shouldBe(false);
        $this->release('order:5');
        $this->isLockReleased('order:5')->shouldBe(true);
    }
}
