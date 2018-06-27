<?php

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
    public function it_has_a_code()
    {
        $error = new TwirpError('code', 'msg');

        $this->assertEquals('code', $error->getErrorCode());
    }

    /**
     * @test
     */
    public function it_has_a_message()
    {
        $error = new TwirpError('code', 'msg');

        $this->assertEquals('msg', $error->getMessage());
    }

    /**
     * @test
     */
    public function it_has_metadata()
    {
        $error = new TwirpError('code', 'msg');
        $error->setMeta('key', 'value');

        $this->assertEquals('value', $error->getMeta('key'));
        $this->assertEquals('', $error->getMeta('invalid_key'));
    }

    /**
     * @test
     */
    public function it_has_a_map_of_metadata()
    {
        $error = new TwirpError('code', 'msg');
        $error->setMeta('key', 'value');

        $this->assertEquals(['key' => 'value'], $error->getMetaMap());
    }

    /**
     * @test
     */
    public function it_creates_a_new_error()
    {
        $error = TwirpError::newError(ErrorCode::Unauthenticated, 'msg');

        $this->assertEquals(ErrorCode::Unauthenticated, $error->getErrorCode());
        $this->assertEquals('msg', $error->getMessage());
    }

    /**
     * @test
     */
    public function it_creates_an_internal_error_when_code_is_invalid()
    {
        $error = TwirpError::newError('code', 'msg');

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('invalid error type code', $error->getMessage());
    }

    /**
     * @test
     */
    public function it_creates_an_error_from_an_exception()
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
    public function it_creates_an_error_from_an_exception_with_a_custom_message()
    {
        $exception = new \Exception('msg');
        $error = TwirpError::errorFrom($exception, 'custom msg');

        $this->assertEquals(ErrorCode::Internal, $error->getErrorCode());
        $this->assertEquals('custom msg', $error->getMessage());
        $this->assertEquals('msg', $error->getMeta('cause'));
    }
}
