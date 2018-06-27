<?php

namespace Twirp;

/**
 * Interface for Twirp errors.
 *
 * @extends \Throwable This interface should extend \Throwable once we reach PHP 7.x.
 */
interface Error
{
    /**
     * One of the valid error codes.
     *
     * getErrorCode is used to avoid collision with \Throwable::getCode.
     *
     * @see ErrorCode
     *
     * @return string
     */
    public function getErrorCode();

    /**
     * Sets or overwrites metadata.
     *
     * @param string $key
     * @param string $value
     */
    public function setMeta($key, $value);

    /**
     * Returns the stored value for the given key. If the key has no set
     * value, Meta returns an empty string. There is no way to distinguish between
     * an unset value and an explicit empty string.
     *
     * @param string $key
     *
     * @return string
     */
    public function getMeta($key);

    /**
     * Returns the complete key-value metadata map stored on the error.
     *
     * @return array
     */
    public function getMetaMap();
}
