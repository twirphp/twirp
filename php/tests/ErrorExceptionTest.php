<?php

namespace Tests\Twirp;

use Twirp\Error;
use Twirp\ErrorException;
use Twirp\Exception;

final class ErrorExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_is_an_exception()
    {
        $error = $this->prophesize(Error::class);
        $e = new ErrorException($error->reveal());

        $this->assertInstanceOf(Exception::class, $e);
        $this->assertInstanceOf(\Exception::class, $e);
    }

    /**
     * @test
     */
    public function it_has_an_error()
    {
        $error = $this->prophesize(Error::class);
        $e = new ErrorException($error = $error->reveal());

        $this->assertSame($error, $e->getError());
    }
}
