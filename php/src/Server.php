<?php

namespace Twirp;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Collects server implementations and routes requests based on their prefix.
 */
final class Server implements RequestHandler
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var RequestHandler[]
     */
    private $handlers = [];

    /**
     * @param ResponseFactoryInterface|null $responseFactory
     * @param StreamFactoryInterface|null   $streamFactory
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        if ($responseFactory === null) {
            $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        }

        if ($streamFactory === null) {
            $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Registers a server instance for a prefix.
     *
     * @param string         $prefix
     * @param RequestHandler $server
     */
    public function registerServer($prefix, RequestHandler $server)
    {
        $this->handlers[$prefix] = $server;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $req)
    {
        foreach ($this->handlers as $prefix => $handler) {
            if (strpos($req->getUri()->getPath(), $prefix) === 0) {
                return $handler->handle($req);
            }
        }

        return $this->writeNoRouteError($req);
    }

    /**
     * Writes no route Twirp error in the response.
     *
     * @param ServerRequestInterface $req
     *
     * @return ResponseInterface
     */
    private function writeNoRouteError(ServerRequestInterface $req)
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode(ErrorCode::BadRoute);

        $body = $this->streamFactory->createStream(json_encode([
            'code' => ErrorCode::BadRoute,
            'msg' => sprintf('no handler for path "%s"', $req->getUri()->getPath()),
            'meta' => [
                'twirp_invalid_route' => $req->getMethod().' '.$req->getUri()->getPath(),
            ],
        ]));

        return $this->responseFactory
            ->createResponse($statusCode)
            ->withHeader('Content-Type', 'application/json') // Error responses are always JSON (instead of protobuf)
            ->withBody($body);
    }
}
