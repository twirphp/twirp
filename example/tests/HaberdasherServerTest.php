<?php

declare(strict_types=1);

namespace Tests\Twitch\Twirp\Example;

use GuzzleHttp\Psr7\ServerRequest;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Twirp\Context;
use Twitch\Twirp\Example\Haberdasher;
use Twitch\Twirp\Example\HaberdasherServer;
use Twitch\Twirp\Example\Hat;
use Twitch\Twirp\Example\Size;

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

        $haberdasherServer->handle($req);

        $this->assertSame($e, $hooks->error);
        $this->assertEquals(500, Context::statusCode($hooks->ctx));
    }

    /**
     * @test
     */
    public function it_accepts_an_empty_path_prefix(): void
    {
        $haberdasher = $this->prophesize(Haberdasher::class);

        $haberdasherServer = new HaberdasherServer($haberdasher->reveal(), null, null, null, '');

        $hat = new Hat();
        $hat->setSize(1);

        $haberdasher->MakeHat(Argument::any(), Argument::type(Size::class))->willReturn($hat);

        $req = new ServerRequest(
            'POST',
            '/twitch.twirp.example.Haberdasher/MakeHat',
            ['Content-Type' => 'application/json'],
            '{}'
        );

        $resp = $haberdasherServer->handle($req);

        $this->assertEquals(200, $resp->getStatusCode());
        $this->assertEquals('{"size":1}', $resp->getBody()->getContents());
    }

    /**
     * @test
     */
    public function it_accepts_a_custom_path_prefix(): void
    {
        $haberdasher = $this->prophesize(Haberdasher::class);

        $haberdasherServer = new HaberdasherServer($haberdasher->reveal(), null, null, null, '/custom/path');

        $hat = new Hat();
        $hat->setSize(1);

        $haberdasher->MakeHat(Argument::any(), Argument::type(Size::class))->willReturn($hat);

        $req = new ServerRequest(
            'POST',
            '/custom/path/twitch.twirp.example.Haberdasher/MakeHat',
            ['Content-Type' => 'application/json'],
            '{}'
        );

        $resp = $haberdasherServer->handle($req);

        $this->assertEquals(200, $resp->getStatusCode());
        $this->assertEquals('{"size":1}', $resp->getBody()->getContents());
    }
}
