<?php

declare(strict_types=1);

namespace Tests\Twitch\Twirp\Example;

use GuzzleHttp\Psr7\ServerRequest;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Twirp\Context;
use Twirp\ServerHooks;
use Twitch\Twirp\Example\Haberdasher;
use Twitch\Twirp\Example\HaberdasherServer;
use Twitch\Twirp\Example\Size;
use Twitch\Twirp\Example\TwirpError;

/**
 * @group example
 */
final class HaberdasherServerTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_returns_an_internal_error_when_the_service_throws_an_exception(): void
    {
        $haberdasher = $this->prophesize(Haberdasher::class);
        $hooks = new ServerHooksErrorStub();

        $haberdasherServer = new HaberdasherServer($haberdasher->reveal(), $hooks);

        $e = new \Exception('message');

        $haberdasher->MakeHat(Argument::any(), Argument::type(Size::class))->willThrow($e);

        $req = new ServerRequest(
        	'POST',
			'/twirp/twitch.twirp.example.Haberdasher/MakeHat',
			['Content-Type' => 'application/json'],
			'{}'
		);

        $resp = $haberdasherServer->handle($req);

        $this->assertSame($e, $hooks->error);
        $this->assertEquals(500, Context::statusCode($hooks->ctx));
    }
}
