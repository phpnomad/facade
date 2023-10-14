<?php

namespace Phoenix\Facade\Tests\Unit\Abstracts;

use Mockery;
use Phoenix\Di\Container;
use Phoenix\Di\Exceptions\DiException;
use Phoenix\Facade\Abstracts\Facade;
use Phoenix\Facade\Tests\TestCase;
use Phoenix\Logger\Interfaces\LoggerStrategy;
use Phoenix\Tests\Traits\WithInaccessibleMethods;
use ReflectionException;

class FacadeTest extends TestCase
{
    use WithInaccessibleMethods;

    /**
     * @var Container&Mockery\MockInterface
     */
    protected $container;

    /**
     * @var Facade<object>&Mockery\MockInterface
     */
    protected $instance;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = Mockery::mock(Container::class);

        $this->instance = Mockery::mock(Facade::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $this->instance->setContainer($this->container);
        $this->instance->allows('abstractInstance')->andReturn(Facade::class);

    }

    /**
     * @covers \Phoenix\Facade\Abstracts\Facade::getContainedInstance()
     * @return void
     * @throws ReflectionException
     */
    public function testCanGetContainedInstance()
    {
        $this->container->expects('get')->with(Facade::class)->andReturn($this->instance);

        $actual = $this->callInaccessibleMethod($this->instance, 'getContainedInstance');

        $this->assertSame($this->instance, $actual);
    }

    /**
     * @covers \Phoenix\Facade\Abstracts\Facade::getContainedInstance()
     * @return void
     * @throws ReflectionException
     */
    public function testGetContainedInstanceLogsExceptions(): void
    {
        $logger = Mockery::mock(LoggerStrategy::class)->makePartial();
        $logger->expects('critical')->with('test', ['container' => $this->container, 'abstract' => Facade::class]);

        $this->container->allows('get')->with(LoggerStrategy::class)->andReturn($logger);
        $this->container->allows('get')->with(Facade::class)->andThrow(new DiException('test'));

        $this->callInaccessibleMethod($this->instance, 'getContainedInstance');
    }
}