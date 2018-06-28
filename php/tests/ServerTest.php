<?php

namespace Tests\Twirp;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Twirp\ErrorCode;
use Twirp\Server;
use Twirp\RequestHandler;

final class ServerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_handles_a_request()
    {
        $handler = $this->prophesize(RequestHandler::class);

        $server = new Server();

        $server->registerServer('/prefix', $handler->reveal());

        $response = new Response();

        $handler->handle(Argument::type(ServerRequestInterface::class))->willReturn($response);

        $request = ServerRequest::fromGlobals();

        $actualResponse = $server->handle($request);

        $this->assertSame($response, $actualResponse);
    }

    /**
     * @test
     */
    public function it_returns_no_route_error_when_a_request_cannot_be_routed_to_a_service()
    {
        $server = new Server();

        $request = ServerRequest::fromGlobals();

        $response = $server->handle($request);

        $body = (string) $response->getBody();
        $body = json_decode($body, true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(ErrorCode::BadRoute, $body['code']);
    }
}
