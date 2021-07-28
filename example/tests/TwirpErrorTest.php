<?php

declare(strict_types=1);

namespace Tests\Twitch\Twirp\Example;

use Twirp\ErrorCode;
use Twitch\Twirp\Example\TwirpError;

/**
 * @group example
 */
final class TwirpErrorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_has_a_code(): void
    {
        $error = new TwirpError('code', 'msg');

        $this->assertEquals('code', $error->getErrorCode());
    }

    /**
     * @test
     */
    public function it_has_a_message(): void
    {
        $error = new TwirpError('code', 'msg');

        $this->assertEquals('msg', $error->getMessage());
    }

    /**
     * @test
     */
    public function it_has_metadata(): void
    {
        $error = new TwirpError('code', 'msg');
        $error->setMeta('key', 'value');

        $this->assertEquals('value', $error->getMeta('key'));
        $this->assertEquals('', $error->getMeta('invalid_key'));
    }

    /**
     * @test
     */
    public function it_has_a_map_of_metadata(): void
    {
        $error = new TwirpError('code', 'msg');
        $error->setMeta('key', 'value');

        $this->assertEquals(['key' => 'value'], $error->getMetaMap());
    }

    /**
     * @test
     */
    public function it_creates_a_new_error(): void
    {
        $error = TwirpError::newError(ErrorCode::Unauthenticated, 'msg');

        $this->assertEquals(ErrorCode::Unauthenticated, $error->getErrorCode());
        $this->assertEquals('msg', $error->getMessage());
    }

    /**
     * @test
     */
    public function it_creates_an_internal_error_when_code_is_invalid(): void
    {
        $error = TwirpError::newError('code', 'msg');

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('invalid error type code', $error->getMessage());
    }

    /**
     * @test
     */
    public function it_creates_an_error_from_an_exception(): void
    {
        $exception = new \Exception('msg');
        $error = TwirpError::errorFrom($exception);

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('msg', $error->getMessage());
        $this->assertEquals('msg', $error->getMeta('cause'));
    }

    /**
     * @test
     */
    public function it_creates_an_error_from_an_exception_with_a_custom_message(): void
    {
        $exception = new \Exception('msg');
        $error = TwirpError::errorFrom($exception, 'custom msg');

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('custom msg', $error->getMessage());
        $this->assertEquals('msg', $error->getMeta('cause'));
    }

    /**
     * Exception->getCode is not guaranteed to return an int, even though the method has a type hint.
     * See: https://www.php.net/manual/en/exception.getcode.php
     * > Returns the exception code as int in Exception but possibly as other
     * > type in Exception descendants (for example as string in PDOException).
     *
     * @test
     */
    public function it_works_with_string_codes(): void
    {
        $exception = new class('msg', 'code') extends \PDOException
        {
            public function __construct(string $msg, string $code)
            {
                parent::__construct($msg);
                $this->code = $code;
            }
        };
        $error = TwirpError::errorFrom($exception);

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('msg', $error->getMessage());
        $this->assertEquals('msg', $error->getMeta('cause'));
        $this->assertEquals(0, $error->getCode());
    }
}
