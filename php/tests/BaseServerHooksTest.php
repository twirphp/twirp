<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Prophecy\PhpUnit\ProphecyTrait;
use Twirp\BaseServerHooks;
use Twirp\Error;

final class BaseServerHooksTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_has_a_request_received_hook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->requestReceived($actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_has_a_request_routed_hook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->requestRouted($actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_has_a_response_prepared_hook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->responsePrepared($actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_has_an_error_hook(): void
    {
        $actual = ['key' => 'value'];

        $expected = (new BaseServerHooks())->error($actual, $this->prophesize(Error::class)->reveal());

        $this->assertEquals($expected, $actual);
    }
}
