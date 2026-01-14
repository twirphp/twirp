<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Method is a generic representation of a Twirp-generated RPC method.
 *
 * It is used to define Interceptors.
 */
interface Method
{
    /**
     * Generic representation of the underlying method call.
     *
     * Since PHP doesn't support function types, it is defined as an interface.
     */
    public function call(array $ctx, object $request): object;
}
