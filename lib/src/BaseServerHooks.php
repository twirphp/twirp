<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Default noop implementation for ServerHooks.
 * Can be used as a base class to implement only a subset of hooks.
 */
class BaseServerHooks implements ServerHooks
{
    public function requestReceived(array $ctx): array
    {
        return $ctx;
    }

    public function requestRouted(array $ctx): array
    {
        return $ctx;
    }

    public function responsePrepared(array $ctx): array
    {
        return $ctx;
    }

    public function responseSent(array $ctx): void
    {
    }

    public function error(array $ctx, \Throwable $error): array
    {
        return $ctx;
    }
}
