<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Interceptor is a form of middleware for Twirp requests, that can be installed on both
 * clients and servers.
 *
 * Just like http middleware, interceptors can mutate requests and responses.
 * This can enable some powerful integrations, but it should be used with much care
 * because it may result in code that is very hard to debug.
 */
interface Interceptor
{
    /**
     * Intercept a request and return an alternate handler (method).
     *
     * The returned method can either wrap the original or can be an entirely new one.
     */
    public function intercept(Method $method): Method;
}
