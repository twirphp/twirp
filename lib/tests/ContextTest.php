<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Twirp\Context;

final class ContextTest extends \PHPUnit\Framework\TestCase
{
    public function testItRetrievesMethodName(): void
    {
        $expected = 'method_name';

        $ctx = [
            Context::METHOD_NAME => $expected,
        ];

        $actual = Context::methodName($ctx);

        self::assertEquals($expected, $actual);
    }

    public function testItReturnsNullWhenThereIsNotMethodName(): void
    {
        self::assertNull(Context::methodName([]));
    }

    public function testItAddsMethodName(): void
    {
        $expected = 'method_name';

        $ctx = Context::withMethodName([], $expected);

        $actual = $ctx[Context::METHOD_NAME];

        self::assertEquals($expected, $actual);
    }

    public function testItRetrievesServiceName(): void
    {
        $expected = 'service_name';

        $ctx = [
            Context::SERVICE_NAME => $expected,
        ];

        $actual = Context::serviceName($ctx);

        self::assertEquals($expected, $actual);
    }

    public function testItReturnsNullWhenThereIsNotServiceName(): void
    {
        self::assertNull(Context::serviceName([]));
    }

    public function testItAddsServiceName(): void
    {
        $expected = 'service_name';

        $ctx = Context::withServiceName([], $expected);

        $actual = $ctx[Context::SERVICE_NAME];

        self::assertEquals($expected, $actual);
    }

    public function testItRetrievesPackageName(): void
    {
        $expected = 'package_name';

        $ctx = [
            Context::PACKAGE_NAME => $expected,
        ];

        $actual = Context::packageName($ctx);

        self::assertEquals($expected, $actual);
    }

    public function testItReturnsNullWhenThereIsNotPackageName(): void
    {
        self::assertNull(Context::packageName([]));
    }

    public function testItAddsPackageName(): void
    {
        $expected = 'package_name';

        $ctx = Context::withPackageName([], $expected);

        $actual = $ctx[Context::PACKAGE_NAME];

        self::assertEquals($expected, $actual);
    }

    public function testItRetrievesStatusCode(): void
    {
        $expected = 200;

        $ctx = [
            Context::STATUS_CODE => $expected,
        ];

        $actual = Context::statusCode($ctx);

        self::assertEquals($expected, $actual);
    }

    public function testItReturnsNullWhenThereIsNotStatusCode(): void
    {
        self::assertNull(Context::statusCode([]));
    }

    public function testItAddsStatusCode(): void
    {
        $expected = 200;

        $ctx = Context::withStatusCode([], $expected);

        $actual = $ctx[Context::STATUS_CODE];

        self::assertEquals($expected, $actual);
    }

    public function testItRetrievesHttpRequestHeaders(): void
    {
        $expected = [
            'Authorization' => 'Bearer 0123456789qwertzuiopasdfghjklyxcvbnm',
        ];

        $ctx = [
            Context::REQUEST_HEADER => $expected,
        ];

        $actual = Context::httpRequestHeaders($ctx);

        self::assertEquals($expected, $actual);
    }

    public function testItReturnsNullWhenThereIsNotHttpRequestHeaders(): void
    {
        self::assertEquals([], Context::httpRequestHeaders([]));
    }

    public function testItAddsHttpRequestHeaders(): void
    {
        $expected = [
            'Authorization' => 'Bearer 0123456789qwertzuiopasdfghjklyxcvbnm',
        ];

        $ctx = Context::withHttpRequestHeaders([], $expected);

        $actual = $ctx[Context::REQUEST_HEADER];

        self::assertEquals($expected, $actual);
    }

    /**
     * @dataProvider twirpRequestHeaderProvider
     */
    public function testItThrowsAnExceptionWhenHttpRequestHeadersContainAHeaderUsedByTwirp(array $headers, string $expectedMessage): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        Context::withHttpRequestHeaders([], $headers);
    }

    public function testItAddsHttpResponseHeader(): void
    {
        $key = 'Authorization';
        $value = 'Bearer 0123456789qwertzuiopasdfghjklyxcvbnm';
        $expected = [
            $key => $value,
        ];

        $ctx = Context::withHttpResponseHeader([], $key, $value);

        $actual = $ctx[Context::RESPONSE_HEADER];

        self::assertEquals($expected, $actual);
    }

    /**
     * @dataProvider twirpResponseHeaderProvider
     */
    public function testItThrowsAnExceptionWhenHttpResponseHeaderIsUsedByTwirp(string $key, string $value, string $expectedMessage): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        Context::withHttpResponseHeader([], $key, $value);
    }

    public function twirpRequestHeaderProvider(): array
    {
        return [
            [
                [
                    'Accept' => 'application/json',
                ],
                'provided header cannot set Accept',
            ],
            [
                [
                    'Content-Type' => 'application/json',
                ],
                'provided header cannot set Content-Type',
            ],
            [
                [
                    'Twirp-Version' => '1.0.0',
                ],
                'provided header cannot set Twirp-Version',
            ],
        ];
    }

    public function twirpResponseHeaderProvider(): array
    {
        return [
            [
                'Content-Type',
                'application/json',
                'header key can not be Content-Type',
            ],
        ];
    }
}
