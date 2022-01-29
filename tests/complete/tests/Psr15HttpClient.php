<?php

declare(strict_types=1);

namespace Tests\Twirp\Complete;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Psr15HttpClient implements ClientInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @var ServerRequestFactoryInterface
     */
    private $requestFactory;

    public function __construct(RequestHandlerInterface $requestHandler, ServerRequestFactoryInterface $requestFactory = null)
    {
        $this->requestHandler = $requestHandler;
        $this->requestFactory = $requestFactory;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $serverRequest = $this->requestFactory->createServerRequest(
            $request->getMethod(),
            $request->getUri(),
        )
            ->withRequestTarget($request->getRequestTarget())
            ->withProtocolVersion($request->getProtocolVersion())
            ->withBody($request->getBody());

        foreach ($request->getHeaders() as $name => $value) {
            $serverRequest = $serverRequest->withHeader($name, $value);
        }

        return $this->requestHandler->handle($serverRequest);
    }
}
