<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Default noop implementation for ServerHooks.
 * Can be used as a base class to implement only a subset of hooks.
 */
class BaseServerHooks implements ServerHooks
{
    /**
     * {@inheritdoc}
     */
    public function requestReceived(array $ctx): array
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function requestRouted(array $ctx): array
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responsePrepared(array $ctx): array
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responseSent(array $ctx): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, \Throwable $error): array
    {
        return $ctx;
    }
}
