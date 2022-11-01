<?php

declare(strict_types=1);

namespace Tests\Twirp;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twirp\ErrorCode;
use Twirp\Router;

final class RouterTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait;

    public function testItHandlesARequest(): void
    {
        $handler = $this->prophesize(RequestHandlerInterface::class);

        $server = new Router();

        $server->registerHandler('/twirp/prefix', $handler->reveal());

        $response = new Response();

        $handler->handle(Argument::type(ServerRequestInterface::class))->willReturn($response);

        $request = ServerRequest::fromGlobals()->withUri(new Uri('http://localhost/twirp/prefix'));

        $actualResponse = $server->handle($request);

        self::assertSame($response, $actualResponse);
    }

    public function testItReturnsNoRouteErrorWhenARequestCannotBeRoutedToAService(): void
    {
        $server = new Router();

        $request = ServerRequest::fromGlobals();

        $response = $server->handle($request);

        $body = (string) $response->getBody();
        $body = json_decode($body, true);

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals(ErrorCode::BadRoute, $body['code']);
    }
}
