<?php

namespace Twirp;

/**
 * Base interface for Twirp exceptions.
 */
interface Exception
{
    /**
     * Returns a twirp error.
     *
     * @return Error
     */
    public function getError();
}
