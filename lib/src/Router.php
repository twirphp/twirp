<?php

declare(strict_types=1);

namespace Twirp;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * A Twirp-compatible multiplexer for serving multiple Twirp services at the same time.
 */
final class Router implements RequestHandlerInterface
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
     * @var RequestHandlerInterface[]
     */
    private $handlers = [];

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

    public function registerHandler(string $path, RequestHandlerInterface $handler): void
    {
        $this->handlers[$path] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->handlers as $path => $handler) {
            if (strpos($request->getUri()->getPath(), $path) === 0) {
                return $handler->handle($request);
            }
        }

        return $this->writeNoRouteError($request);
    }

    /**
     * Writes no route Twirp error in the response.
     */
    private function writeNoRouteError(ServerRequestInterface $request): ResponseInterface
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode(ErrorCode::BadRoute);

        $body = $this->streamFactory->createStream(json_encode([
            'code' => ErrorCode::BadRoute,
            'msg' => sprintf('no handler for path "%s"', $request->getUri()->getPath()),
            'meta' => [
                'twirp_invalid_route' => $request->getMethod().' '.$request->getUri()->getPath(),
            ],
        ]));

        return $this->responseFactory
            ->createResponse($statusCode)
            ->withHeader('Content-Type', 'application/json') // Error responses are always JSON (instead of protobuf)
            ->withBody($body);
    }
}
