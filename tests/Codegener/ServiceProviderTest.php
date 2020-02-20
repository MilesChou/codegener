<?php

namespace Tests\Codegener;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider;
use MilesChou\Codegener\ServiceProvider;
use MilesChou\Codegener\Writer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
    }

    protected function tearDown(): void
    {
        $this->container = null;

        parent::tearDown();
    }

    /**
     * @test
     */
    public function shouldAutoInjectWhenNothingBindInContainer(): void
    {
        (new ServiceProvider($this->container))->register();

        $this->assertInstanceOf(Writer::class, $this->container->make(Writer::class));
    }

    public function abstractLoggers()
    {
        yield 'Laravel default logger' => ['log'];
        yield 'Custom logger' => [LoggerInterface::class];
    }

    /**
     * @test
     * @dataProvider abstractLoggers
     */
    public function shouldUseLaravelDefaultLoggerWhenBound($abstractLogger): void
    {
        $spy = new TestLogger();

        $this->container->instance($abstractLogger, $spy);

        (new ServiceProvider($this->container))->register();

        /** @var Writer $writer */
        $writer = $this->container->make(Writer::class);
        $writer->write($this->vfs->url() . '/whatever', 'something');

        $this->assertTrue($spy->hasInfoRecords());
    }

    /**
     * @test
     */
    public function shouldUseLaravelDefaultFilesystemWhenBound(): void
    {
        (new FilesystemServiceProvider($this->container))->register();
        (new ServiceProvider($this->container))->register();

        $this->assertInstanceOf(Writer::class, $this->container->make(Writer::class));
    }
}
