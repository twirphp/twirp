<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Context key constants and access logic.
 */
final class Context
{
    public const METHOD_NAME = 'method_name';
    public const SERVICE_NAME = 'service_name';
    public const PACKAGE_NAME = 'package_name';
    public const STATUS_CODE = 'status_code';
    public const REQUEST_HEADER = 'request_header';
    public const RESPONSE_HEADER = 'response_header';

    /**
     * Extracts the name of the method being handled in the given
     * context. If it is not known, it returns null.
     */
    public static function methodName(array $ctx): ?string
    {
        if (isset($ctx[self::METHOD_NAME])) {
            return $ctx[self::METHOD_NAME];
        }

        return null;
    }

    /**
     * Sets the method name in the context.
     */
    public static function withMethodName(array $ctx, string $name): array
    {
        $ctx[self::METHOD_NAME] = $name;

        return $ctx;
    }

    /**
     * Extracts the name of the service handling the given context. If
     * it is not known, it returns null.
     */
    public static function serviceName(array $ctx): ?string
    {
        if (isset($ctx[self::SERVICE_NAME])) {
            return $ctx[self::SERVICE_NAME];
        }

        return null;
    }

    /**
     * Sets the service name in the context.
     */
    public static function withServiceName(array $ctx, string $name): array
    {
        $ctx[self::SERVICE_NAME] = $name;

        return $ctx;
    }

    /**
     * Extracts the fully-qualified protobuf package name of the service
     * handling the given context. If it is not known, it returns null. If
     * the service comes from a proto file that does not declare a package name, it
     * returns "".
     *
     * Note that the protobuf package name can be very different than the go package
     * name; the two are unrelated.
     */
    public static function packageName(array $ctx): ?string
    {
        if (isset($ctx[self::PACKAGE_NAME])) {
            return $ctx[self::PACKAGE_NAME];
        }

        return null;
    }

    /**
     * Sets the package name in the context.
     */
    public static function withPackageName(array $ctx, string $name): array
    {
        $ctx[self::PACKAGE_NAME] = $name;

        return $ctx;
    }

    /**
     * Retrieves the status code of the response (as string like "200").
     * If it is known returns the status.
     * If it is not known, it returns null.
     */
    public static function statusCode(array $ctx): ?int
    {
        if (isset($ctx[self::STATUS_CODE])) {
            return $ctx[self::STATUS_CODE];
        }

        return null;
    }

    /**
     * Sets the status code in the context.
     */
    public static function withStatusCode(array $ctx, int $code): array
    {
        $ctx[self::STATUS_CODE] = $code;

        return $ctx;
    }

    /**
     * Retrieves the HTTP headers sent as part of the request.
     * If there are no headers, it returns an empty array.
     */
    public static function httpRequestHeaders(array $ctx): array
    {
        if (isset($ctx[self::REQUEST_HEADER])) {
            return $ctx[self::REQUEST_HEADER];
        }

        return [];
    }

    /**
     * Stores an HTTP headers in a context. When
     * using a Twirp-generated client, you can pass the returned context
     * into any of the request methods, and the stored header will be
     * included in outbound HTTP requests.
     *
     * This can be used to set custom HTTP headers like authorization tokens or
     * client IDs. But note that HTTP headers are a Twirp implementation detail,
     * only visible by middleware, not by the server implementation.
     *
     * Throws an exception if the provided headers
     * would overwrite a header that is needed by Twirp, like "Content-Type".
     *
     * @throws \InvalidArgumentException when any of the following headers are included: Accept, Content-Type, Twirp-Version
     */
    public static function withHttpRequestHeaders(array $ctx, array $headers): array
    {
        foreach ($headers as $key => $value) {
            $key = strtolower($key);
            $msg = 'provided header cannot set %s';

            switch ($key) {
                case 'accept':
                    throw new \InvalidArgumentException(sprintf($msg, 'Accept'));
                case 'content-type':
                    throw new \InvalidArgumentException(sprintf($msg, 'Content-Type'));
                case 'twirp-version':
                    throw new \InvalidArgumentException(sprintf($msg, 'Twirp-Version'));
            }
        }

        $ctx[self::REQUEST_HEADER] = $headers;

        return $ctx;
    }

    /**
     * Sets an HTTP header key-value pair using a context
     * provided by a twirp-generated server, or a child of that context.
     * The server will include the header in its response for that request context.
     *
     * This can be used to respond with custom HTTP headers like "Cache-Control".
     * But note that HTTP headers are a Twirp implementation detail,
     * only visible by middleware, not by the clients or their responses.
     *
     * If called multiple times with the same key, it replaces any existing values
     * associated with that key.
     *
     * Throws an exception if the provided headers
     * would overwrite a header that is needed by Twirp, like "Content-Type".
     *
     * @throws \InvalidArgumentException when any of the following headers are included: Content-Type
     */
    public static function withHttpResponseHeader(array $ctx, string $key, string $value): array
    {
        if (strtolower($key) === 'content-type') {
            throw new \InvalidArgumentException('header key can not be Content-Type');
        }

        $ctx[self::RESPONSE_HEADER][$key] = $value;

        return $ctx;
    }
}
