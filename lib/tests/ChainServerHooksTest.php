<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Twirp\ChainServerHooks;
use Twirp\Error;
use Twirp\ServerHooks;

final class ChainServerHooksTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait;

    /**
     * @var ChainServerHooks
     */
    private $hook;

    /**
     * @var ServerHooks|ObjectProphecy
     */
    private $hook1;

    /**
     * @var ServerHooks|ObjectProphecy
     */
    private $hook2;

    protected function setUp(): void
    {
        $this->hook1 = $this->prophesize(ServerHooks::class);
        $this->hook2 = $this->prophesize(ServerHooks::class);

        $this->hook = new ChainServerHooks($this->hook1->reveal(), $this->hook2->reveal());
    }

    public function testItHasARequestReceivedHook(): void
    {
        $actual = ['key' => 'value'];

        $this->hook1->requestReceived([])->willReturn(['key2' => 'value2']);
        $this->hook2->requestReceived(['key2' => 'value2'])->willReturn($actual);

        $expected = $this->hook->requestReceived([]);

        self::assertEquals($expected, $actual);
    }

    public function testItHasARequestRoutedHook(): void
    {
        $actual = ['key' => 'value'];

        $this->hook1->requestRouted([])->willReturn(['key2' => 'value2']);
        $this->hook2->requestRouted(['key2' => 'value2'])->willReturn($actual);

        $expected = $this->hook->requestRouted([]);

        self::assertEquals($expected, $actual);
    }

    public function testItHasAResponsePreparedHook(): void
    {
        $actual = ['key' => 'value'];

        $this->hook1->responsePrepared([])->willReturn(['key2' => 'value2']);
        $this->hook2->responsePrepared(['key2' => 'value2'])->willReturn($actual);

        $expected = $this->hook->responsePrepared([]);

        self::assertEquals($expected, $actual);
    }

    public function testItHasAResponseSentHook(): void
    {
        $ctx = ['key' => 'value'];

        $this->hook1->responseSent($ctx)->shouldBeCalled();
        $this->hook2->responseSent($ctx)->shouldBeCalled();

        $this->hook->responseSent($ctx);
    }

    public function testItHasAnErrorHook(): void
    {
        $actual = ['key' => 'value'];
        $error = $this->prophesize(Error::class)->reveal();

        $this->hook1->error([], $error)->willReturn(['key2' => 'value2']);
        $this->hook2->error(['key2' => 'value2'], $error)->willReturn($actual);

        $expected = $this->hook->error([], $error);

        self::assertEquals($expected, $actual);
    }
}
