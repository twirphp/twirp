<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Twirp\Interceptor;
use Twirp\InterceptorChain;
use Twirp\Method;

final class InterceptorChainTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait;

    /**
     * @var InterceptorChain
     */
    private $interceptor;

    /**
     * @var Interceptor|ObjectProphecy
     */
    private $interceptor1;

    /**
     * @var Interceptor|ObjectProphecy
     */
    private $interceptor2;

    public function setUp(): void
    {
        $this->interceptor1 = $this->prophesize(Interceptor::class);
        $this->interceptor2 = $this->prophesize(Interceptor::class);

        $this->interceptor = new InterceptorChain($this->interceptor1->reveal(), $this->interceptor2->reveal());
    }

    /**
     * @test
     */
    public function it_intercepts_a_method(): void
    {
        $method1 = $this->prophesize(Method::class)->reveal();
        $method2 = $this->prophesize(Method::class)->reveal();

        $this->interceptor1->intercept($method1)->willReturn($method2);
        $this->interceptor2->intercept($method2)->willReturn($method2);

        self::assertSame($method2, $this->interceptor->intercept($method1));
    }
}
