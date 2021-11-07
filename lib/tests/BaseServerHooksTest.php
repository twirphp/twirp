<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Prophecy\PhpUnit\ProphecyTrait;
use Twirp\BaseServerHooks;
use Twirp\Error;

final class BaseServerHooksTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait;

    public function testItHasARequestReceivedHook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->requestReceived($actual);

        self::assertEquals($expected, $actual);
    }

    public function testItHasARequestRoutedHook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->requestRouted($actual);

        self::assertEquals($expected, $actual);
    }

    public function testItHasAResponsePreparedHook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->responsePrepared($actual);

        self::assertEquals($expected, $actual);
    }

    public function testItHasAnErrorHook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->error($actual, $this->prophesize(Error::class)->reveal());

        self::assertEquals($expected, $actual);
    }
}
