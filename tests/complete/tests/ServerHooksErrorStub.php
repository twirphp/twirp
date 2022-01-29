<?php

declare(strict_types=1);

namespace Tests\Twirp\Complete;

use Twirp\BaseServerHooks;

final class ServerHooksErrorStub extends BaseServerHooks
{
    public $ctx;
    public $error;

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, \Throwable $error): array
    {
        $this->ctx = $ctx;
        $this->error = $error;

        return $ctx;
    }
}
