<?php

declare(strict_types=1);

namespace Twirp;

use Psr\Http\Message\RequestInterface;

/**
 * Method is a generic representation of a Twirp-generated RPC method.
 *
 * It is used to define Interceptors.
 */
class Method
{
    /**
     * @var array
     */
    protected $ctx;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Generic representation of the underlying method call.
     */
    public function __construct(array $ctx, RequestInterface $request)
    {
        $this->ctx = $ctx;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getCtx(): array
    {
        return $this->ctx;
    }

    /**
     * @param array $ctx
     */
    public function setCtx(array $ctx): void
    {
        $this->ctx = $ctx;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }
}
