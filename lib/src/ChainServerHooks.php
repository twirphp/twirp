<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Server hook multiplexer.
 */
final class ChainServerHooks implements ServerHooks
{
    /**
     * @var ServerHooks[]
     */
    private $hooks = [];

    public function __construct(ServerHooks ...$hooks)
    {
        $this->hooks = $hooks;
    }

    /**
     * {@inheritdoc}
     */
    public function requestReceived(array $ctx): array
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->requestReceived($ctx);
        }

        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function requestRouted(array $ctx): array
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->requestRouted($ctx);
        }

        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responsePrepared(array $ctx): array
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->responsePrepared($ctx);
        }

        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responseSent(array $ctx): void
    {
        foreach ($this->hooks as $hook) {
            $hook->responseSent($ctx);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, \Throwable $error): array
    {
        foreach ($this->hooks as $hook) {
            $ctx = $hook->error($ctx, $error);
        }

        return $ctx;
    }
}
