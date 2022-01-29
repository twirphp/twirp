<?php

declare(strict_types=1);

namespace Tests\Twirp\Complete;

use GuzzleHttp\Psr7\HttpFactory;
use Twirp\Error;
use Twirp\Router;
use Twirp\Tests\Complete\Haberdasher;
use Twirp\Tests\Complete\Proto\HaberdasherClient;
use Twirp\Tests\Complete\Proto\HaberdasherServer;
use Twirp\Tests\Complete\Proto\Size;
use Twirp\Tests\Complete\Proto\TwirpError;

/**
 * @group example
 */
final class HaberdasherFunctionalTest extends \PHPUnit\Framework\TestCase
{
    public function testItReturnsAResponseWithDefaultSettings(): void
    {
        $haberdasherServer = new HaberdasherServer(new Haberdasher());

        $httpClient = new Psr15HttpClient($haberdasherServer, new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient);

        $hat = $haberdasherClient->MakeHat([], (new Size())->setInches(123));

        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }

    public function testItReturnsANotFoundResponseWhenNoHandlersAreRegistered(): void
    {
        $httpClient = new Psr15HttpClient(new Router(), new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient);

        $this->expectException(Error::class);
        $this->expectException(TwirpError::class);
        $this->expectExceptionMessage('no handler for path "/twirp/twirp.tests.complete.proto.Haberdasher/MakeHat"');

        $haberdasherClient->MakeHat([], (new Size())->setInches(123));
    }

    public function testItReturnsAResponseWithEmptyPrefix(): void
    {
        $haberdasherServer = new HaberdasherServer(new Haberdasher(), null, null, null, '');

        $httpClient = new Psr15HttpClient($haberdasherServer, new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient, null, null, '');

        $hat = $haberdasherClient->MakeHat([], (new Size())->setInches(123));

        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }

    public function testItReturnsAResponseWithCustomPrefix(): void
    {
        $haberdasherServer = new HaberdasherServer(new Haberdasher(), null, null, null, '/custom/prefix');

        $httpClient = new Psr15HttpClient($haberdasherServer, new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient, null, null, '/custom/prefix');

        $hat = $haberdasherClient->MakeHat([], (new Size())->setInches(123));

        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }

    public function testItReturnsABadPathErrorWhenThePrefixDoesNotMatch(): void
    {
        $haberdasherServer = new HaberdasherServer(new Haberdasher());

        $httpClient = new Psr15HttpClient($haberdasherServer, new HttpFactory());

        $haberdasherClient = new HaberdasherClient('www.example.com', $httpClient, null, null, '/not-twirp');

        $this->expectException(Error::class);
        $this->expectException(TwirpError::class);
        $this->expectExceptionMessage('invalid path prefix "/not-twirp", expected "/twirp", on path "/not-twirp/twirp.tests.complete.proto.Haberdasher/MakeHat"');

        $haberdasherClient->MakeHat([], (new Size())->setInches(123));
    }
}
