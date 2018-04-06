<?php

namespace Twirp;

/**
 * Base interface for Twirp errors.
 */
interface Error
{
    /**
     * One of the valid error codes.
     *
     * @see ErrorCode
     *
     * @return string
     */
    public function code();

    /**
     * Returns a human-readable, unstructured messages describing the error.
     *
     * @return string
     */
    public function msg();

    /**
     * Returns a copy of the Error with the given key-value pair attached
     * as metadata. If the key is already set, it is overwritten.
     *
     * @param string $key
     * @param string $val
     *
     * @return Error
     */
    public function withMeta($key, $val);

    /**
     * Returns the stored value for the given key. If the key has no set
     * value, Meta returns an empty string. There is no way to distinguish between
     * an unset value and an explicit empty string.
     *
     * @param string $key
     *
     * @return string
     */
    public function meta($key);

    /**
     * Returns the complete key-value metadata map stored on the error.
     *
     * @return array
     */
    public function metaMap();
}
