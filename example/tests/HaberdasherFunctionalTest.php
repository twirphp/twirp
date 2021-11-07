<?php

declare(strict_types=1);

namespace Tests\Twitch\Twirp\Example;

use GuzzleHttp\Psr7\HttpFactory;
use Twirphp\Example\Haberdasher;
use Twirp\Error;
use Twirp\Router;
use Twitch\Twirp\Example\HaberdasherClient;
use Twitch\Twirp\Example\HaberdasherServer;
use Twitch\Twirp\Example\Size;
use Twitch\Twirp\Example\TwirpError;

/**
 * @group example
 */
final class HaberdasherFunctionalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_returns_a_response_with_default_settings(): void
    {
        $haberdasherServer = new HaberdasherServer(new Haberdasher());

        $httpClient = new Psr15HttpClient($haberdasherServer, new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient);

        $hat = $haberdasherClient->MakeHat([], (new Size)->setInches(123));

        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }

    /**
     * @test
     */
    public function it_returns_a_not_found_response_when_no_handlers_are_registered(): void
    {
        $httpClient = new Psr15HttpClient(new Router(), new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient);

        $this->expectException(Error::class);
        $this->expectException(TwirpError::class);
        $this->expectExceptionMessage('no handler for path "/twirp/twitch.twirp.example.Haberdasher/MakeHat"');

        $haberdasherClient->MakeHat([], (new Size)->setInches(123));
    }

    /**
     * @test
     */
    public function it_returns_a_response_with_empty_prefix(): void
    {
        $haberdasherServer = new HaberdasherServer(new Haberdasher(), null, null, null, '');

        $httpClient = new Psr15HttpClient($haberdasherServer, new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient, null, null, '');

        $hat = $haberdasherClient->MakeHat([], (new Size)->setInches(123));

        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }

    /**
     * @test
     */
    public function it_returns_a_response_with_custom_prefix(): void
    {
        $haberdasherServer = new HaberdasherServer(new Haberdasher(), null, null, null, '/custom/prefix');

        $httpClient = new Psr15HttpClient($haberdasherServer, new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient, null, null, '/custom/prefix');

        $hat = $haberdasherClient->MakeHat([], (new Size)->setInches(123));

        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }
}
