<?php

declare(strict_types=1);

namespace Twirp;

interface ServerWithPathPrefix
{
    /**
     * Returns the base service path, in the form: "/<prefix>/<package>.<Service>/"
     * that is everything in a Twirp route except for the <Method>. This can be used for routing,
     * for example, to identify the requests that are targeted to this service in a mux.
     */
    public function getPathPrefix(): string;
}
