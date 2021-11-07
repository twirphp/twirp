<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Twirp\ErrorCode;

final class ErrorCodeTest extends \PHPUnit\Framework\TestCase
{
    public function testItReturnsServerHttpstatusFromErrorCode(): void
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode(ErrorCode::NoError);

        self::assertEquals(200, $statusCode);
    }

    public function testItChecksIfErrorCodeIsValid(): void
    {
        self::assertTrue(ErrorCode::isValid(ErrorCode::NoError));
        self::assertFalse(ErrorCode::isValid('invalid_code_for_sure'));
    }
}
