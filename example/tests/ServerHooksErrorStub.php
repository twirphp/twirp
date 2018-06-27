<?php

namespace Tests\Twitch\Twirp\Example;

use Twirp\BaseServerHooks;

final class ServerHooksErrorStub extends BaseServerHooks
{
    public $ctx;
    public $error;

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, $error)
    {
        $this->ctx = $ctx;
        $this->error = $error;

        return $ctx;
    }
}
