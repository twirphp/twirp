<?php

declare(strict_types=1);

namespace Tests\Twirp\Complete;

use Twirp\ErrorCode;
use Twirp\Tests\Complete\Proto\TwirpError;

final class TwirpErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testItHasACode(): void
    {
        $error = new TwirpError('code', 'msg');

        $this->assertEquals('code', $error->getErrorCode());
    }

    public function testItHasAMessage(): void
    {
        $error = new TwirpError('code', 'msg');

        $this->assertEquals('msg', $error->getMessage());
    }

    public function testItHasMetadata(): void
    {
        $error = new TwirpError('code', 'msg');
        $error->setMeta('key', 'value');

        $this->assertEquals('value', $error->getMeta('key'));
        $this->assertEquals('', $error->getMeta('invalid_key'));
    }

    public function testItHasAMapOfMetadata(): void
    {
        $error = new TwirpError('code', 'msg');
        $error->setMeta('key', 'value');

        $this->assertEquals(['key' => 'value'], $error->getMetaMap());
    }

    public function testItCreatesANewError(): void
    {
        $error = TwirpError::newError(ErrorCode::Unauthenticated, 'msg');

        $this->assertEquals(ErrorCode::Unauthenticated, $error->getErrorCode());
        $this->assertEquals('msg', $error->getMessage());
    }

    public function testItCreatesAnInternalErrorWhenCodeIsInvalid(): void
    {
        $error = TwirpError::newError('code', 'msg');

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('invalid error type code', $error->getMessage());
    }

    public function testItCreatesAnErrorFromAnException(): void
    {
        $exception = new \Exception('msg');
        $error = TwirpError::errorFrom($exception);

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('msg', $error->getMessage());
        $this->assertEquals('msg', $error->getMeta('cause'));
    }

    public function testItCreatesAnErrorFromAnExceptionWithACustomMessage(): void
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
     */
    public function testItWorksWithStringCodes(): void
    {
        $exception = new class('msg', 'code') extends \PDOException {
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
