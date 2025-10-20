<?php

declare(strict_types=1);

namespace Twirp;

interface CanEmitJsonDefaults
{
    public function setEmitJsonDefaults(bool $emitDefaults): void;

    public function shouldEmitJsonDefaults(): bool;
}
