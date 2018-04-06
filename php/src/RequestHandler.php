<?php

namespace Twirp;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * An HTTP request handler processes an HTTP request and produces an HTTP response.
 * This interface defines the methods required to use the request handler.
 *
 * This interface is backported from PSR-18 to make it PHP 5.6 compatible.
 * In a future version it may be replaced with PSR-18 entirely.
 */
interface RequestHandler
{
    /**
     * Handle the request and return a response.
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request);
}
