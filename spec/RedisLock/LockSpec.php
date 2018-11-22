<?php

namespace spec\RedisLock;

use PhpSpec\ObjectBehavior;
use Predis\Client;
use RedisLock\Lock;
use RedisLock\RedisLockInterface;

class LockSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new Client());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Lock::class);
        $this->shouldImplement(RedisLockInterface::class);
    }

    public function it_can_set_default_ttl()
    {
        $this->setDefaultTTL(3000)->shouldReturn(true);
    }

    public function it_can_get_default_ttl()
    {
        $this->setDefaultTTL(3000)->shouldReturn(true);
        $this->getDefaultTTL()->shouldReturn(3000);
    }

    public function it_lock_key()
    {
        $this->lock('order:123', 1000)->shouldReturn('OK');
    }

    public function it_get_key()
    {
        $this->lock('order:1', 1000);
        $this->get('order:1')->shouldBeString();
    }

    public function it_release_lock()
    {
        $this->lock('order:30', 1000);
        $randString = $this->getRandomString();
        $this->get('order:30')->shouldBe($randString);
        $this->release('order:30');
        $this->get('order:30')->shouldReturn(null);
    }

    public function it_is_lock_released()
    {
        $this->isLockReleased('order:3')->shouldBe(true);
        $this->lock('order:5');
        $this->isLockReleased('order:5')->shouldBe(false);
        $this->release('order:5');
        $this->isLockReleased('order:5')->shouldBe(true);
    }
}
