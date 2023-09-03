<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Chain multiple Interceptors into a single Interceptor.
 */
final class InterceptorChain implements Interceptor
{
    /**
     * @var Interceptor[]
     */
    private $interceptors = [];

    public function __construct(Interceptor ...$interceptors)
    {
        $this->interceptors = $interceptors;
    }

    /**
     * {@inheritdoc}
     */
    public function intercept(Method $method): Method
    {
        foreach ($this->interceptors as $interceptor) {
            $method = $interceptor->intercept($method);
        }

        return $method;
    }
}
