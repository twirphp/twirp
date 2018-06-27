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

        $this->assertEquals('code', $error->code());
    }

    /**
     * @test
     */
    public function it_has_a_message()
    {
        $error = new TwirpError('code', 'msg');

        $this->assertEquals('msg', $error->msg());
    }

    /**
     * @test
     */
    public function it_has_metadata()
    {
        $error = new TwirpError('code', 'msg');
        $error->withMeta('key', 'value');

        $this->assertEquals('value', $error->meta('key'));
        $this->assertEquals('', $error->meta('invalid_key'));
    }

    /**
     * @test
     */
    public function it_has_a_map_of_metadata()
    {
        $error = new TwirpError('code', 'msg');
        $error->withMeta('key', 'value');

        $this->assertEquals(['key' => 'value'], $error->metaMap());
    }

    /**
     * @test
     */
    public function it_creates_a_new_error()
    {
        $error = TwirpError::newError(ErrorCode::Unauthenticated, 'msg');

        $this->assertEquals(ErrorCode::Unauthenticated, $error->code());
        $this->assertEquals('msg', $error->msg());
    }

    /**
     * @test
     */
    public function it_creates_an_internal_error_when_code_is_invalid()
    {
        $error = TwirpError::newError('code', 'msg');

        $this->assertEquals(ErrorCode::Internal, $error->code());
        $this->assertEquals('invalid error type code', $error->msg());
    }

    /**
     * @test
     */
    public function it_creates_an_error_from_an_exception()
    {
        $exception = new \Exception('msg');
        $error = TwirpError::errorFromException($exception);

        $this->assertEquals(ErrorCode::Internal, $error->code());
        $this->assertEquals('msg', $error->msg());
        $this->assertEquals('msg', $error->meta('cause'));
    }

    /**
     * @test
     */
    public function it_creates_an_error_from_an_exception_with_a_custom_message()
    {
        $exception = new \Exception('msg');
        $error = TwirpError::errorFromException($exception, 'custom msg');

        $this->assertEquals(ErrorCode::Internal, $error->code());
        $this->assertEquals('custom msg', $error->msg());
        $this->assertEquals('msg', $error->meta('cause'));
    }
}
