<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Interface for Twirp errors.
 */
interface Error extends \Throwable
{
    /**
     * One of the valid error codes.
     *
     * getErrorCode is used to avoid collision with \Throwable::getCode.
     *
     * @see ErrorCode
     */
    public function getErrorCode(): string;

    /**
     * Sets or overwrites metadata.
     */
    public function setMeta(string $key, string $value): void;

    /**
     * Returns the stored value for the given key. If the key has no set
     * value, Meta returns an empty string. There is no way to distinguish between
     * an unset value and an explicit empty string.
     */
    public function getMeta(string $key): string;

    /**
     * Returns the complete key-value metadata map stored on the error.
     */
    public function getMetaMap(): array;
}
