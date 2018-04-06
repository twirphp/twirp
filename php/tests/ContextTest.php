<?php

namespace Tests\Twirp;

use Twirp\Context;

final class ContextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_retrieves_method_name()
    {
        $expected = 'method_name';

        $ctx = [
            Context::METHOD_NAME => $expected,
        ];

        $actual = Context::methodName($ctx);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_returns_null_when_there_is_not_method_name()
    {
        $this->assertNull(Context::methodName([]));
    }

    /**
     * @test
     */
    public function it_adds_method_name()
    {
        $expected = 'method_name';

        $ctx = Context::withMethodName([], $expected);

        $actual = $ctx[Context::METHOD_NAME];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_retrieves_service_name()
    {
        $expected = 'service_name';

        $ctx = [
            Context::SERVICE_NAME => $expected,
        ];

        $actual = Context::serviceName($ctx);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_returns_null_when_there_is_not_service_name()
    {
        $this->assertNull(Context::serviceName([]));
    }

    /**
     * @test
     */
    public function it_adds_service_name()
    {
        $expected = 'service_name';

        $ctx = Context::withServiceName([], $expected);

        $actual = $ctx[Context::SERVICE_NAME];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_retrieves_package_name()
    {
        $expected = 'package_name';

        $ctx = [
            Context::PACKAGE_NAME => $expected,
        ];

        $actual = Context::packageName($ctx);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_returns_null_when_there_is_not_package_name()
    {
        $this->assertNull(Context::packageName([]));
    }

    /**
     * @test
     */
    public function it_adds_package_name()
    {
        $expected = 'package_name';

        $ctx = Context::withPackageName([], $expected);

        $actual = $ctx[Context::PACKAGE_NAME];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_retrieves_status_code()
    {
        $expected = 200;

        $ctx = [
            Context::STATUS_CODE => $expected,
        ];

        $actual = Context::statusCode($ctx);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_returns_null_when_there_is_not_status_code()
    {
        $this->assertNull(Context::statusCode([]));
    }

    /**
     * @test
     */
    public function it_adds_status_code()
    {
        $expected = 200;

        $ctx = Context::withStatusCode([], $expected);

        $actual = $ctx[Context::STATUS_CODE];

        $this->assertEquals($expected, $actual);
    }
}
