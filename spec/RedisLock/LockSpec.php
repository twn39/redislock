<?php

namespace spec\RedisLock;

use Predis\Client;
use RedisLock\Lock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LockSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Client());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Lock::class);
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
        $this->lock('order:123', 1000);
        $this->get('order:123')->shouldBeString();
        $this->release('order:123');
        $this->get('order:123')->shouldReturn(null);
    }
}
