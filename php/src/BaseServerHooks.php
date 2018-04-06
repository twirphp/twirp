<?php

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
    public function requestReceived(array $ctx)
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function requestRouted(array $ctx)
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responsePrepared(array $ctx)
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responseSent(array $ctx)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, Error $error)
    {
        return $ctx;
    }
}
