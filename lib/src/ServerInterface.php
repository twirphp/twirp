<?php

namespace Twirp;

use Psr\Http\Server\RequestHandlerInterface;

interface ServerInterface extends RequestHandlerInterface
{
    /**
     * Returns the base service path, in the form: "/<prefix>/<package>.<Service>/"
     * that is everything in a Twirp route except for the <Method>. This can be used for routing,
     * for example to identify the requests that are targeted to this service in a mux.
     */
    public function getPathPrefix(): string;
}
