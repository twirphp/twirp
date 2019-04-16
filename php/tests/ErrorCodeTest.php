<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Twirp\ErrorCode;

final class ErrorCodeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_returns_server_httpstatus_from_error_code(): void
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode(ErrorCode::NoError);

        $this->assertEquals(200, $statusCode);
    }

    /**
     * @test
     */
    public function it_checks_if_error_code_is_valid(): void
    {
        $this->assertTrue(ErrorCode::isValid(ErrorCode::NoError));
        $this->assertFalse(ErrorCode::isValid('invalid_code_for_sure'));
    }
}
