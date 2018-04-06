<?php

namespace Twirp;

/**
 * Context key constants and access logic.
 */
final class Context
{
    const METHOD_NAME = 'method_name';
    const SERVICE_NAME = 'service_name';
    const PACKAGE_NAME = 'package_name';
    const STATUS_CODE = 'status_code';

    /**
     * Extracts the name of the method being handled in the given
     * context. If it is not known, it returns null.
     *
     * @param array $ctx
     *
     * @return string
     */
    public static function methodName(array $ctx)
    {
        if (isset($ctx[self::METHOD_NAME])) {
            return $ctx[self::METHOD_NAME];
        }

        return null;
    }

    /**
     * Sets the method name in the context.
     *
     * @param array  $ctx
     * @param string $name
     *
     * @return array
     */
    public static function withMethodName(array $ctx, $name)
    {
        $ctx[self::METHOD_NAME] = $name;

        return $ctx;
    }

    /**
     * Extracts the name of the service handling the given context. If
     * it is not known, it returns null.
     *
     * @param array $ctx
     *
     * @return string
     */
    public static function serviceName(array $ctx)
    {
        if (isset($ctx[self::SERVICE_NAME])) {
            return $ctx[self::SERVICE_NAME];
        }

        return null;
    }

    /**
     * Sets the service name in the context.
     *
     * @param array  $ctx
     * @param string $name
     *
     * @return array
     */
    public static function withServiceName(array $ctx, $name)
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
     *
     * @param array $ctx
     *
     * @return string
     */
    public static function packageName(array $ctx)
    {
        if (isset($ctx[self::PACKAGE_NAME])) {
            return $ctx[self::PACKAGE_NAME];
        }

        return null;
    }

    /**
     * Sets the package name in the context.
     *
     * @param array  $ctx
     * @param string $name
     *
     * @return array
     */
    public static function withPackageName(array $ctx, $name)
    {
        $ctx[self::PACKAGE_NAME] = $name;

        return $ctx;
    }

    /**
     * Retrieves the status code of the response (as string like "200").
     * If it is known returns the status.
     * If it is not known, it returns null.
     *
     * @param array $ctx
     *
     * @return int
     */
    public static function statusCode(array $ctx)
    {
        if (isset($ctx[self::STATUS_CODE])) {
            return $ctx[self::STATUS_CODE];
        }

        return null;
    }

    /**
     * Sets the status code in the context.
     *
     * @param array $ctx
     * @param int   $code
     *
     * @return array
     */
    public static function withStatusCode(array $ctx, $code)
    {
        $ctx[self::STATUS_CODE] = $code;

        return $ctx;
    }
}
