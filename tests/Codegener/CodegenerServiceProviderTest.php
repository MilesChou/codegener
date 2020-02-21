<?php

namespace Tests\Codegener;

use Illuminate\Container\Container;
use Illuminate\Filesystem\FilesystemServiceProvider;
use MilesChou\Codegener\CodegenerServiceProvider;
use MilesChou\Codegener\Writer;
use Psr\Log\LoggerInterface;
use Psr\Log\Test\TestLogger;
use Tests\TestCase;

class CodegenerServiceProviderTest extends TestCase
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
        (new CodegenerServiceProvider($this->container))->register();

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

        (new CodegenerServiceProvider($this->container))->register();

        /** @var Writer $writer */
        $writer = $this->container->make(Writer::class);
        $writer->setBasePath($this->vfs->url());
        $writer->write('whatever', 'something');

        $this->assertTrue($spy->hasInfoRecords());
    }

    /**
     * @test
     */
    public function shouldUseLaravelDefaultFilesystemWhenBound(): void
    {
        (new FilesystemServiceProvider($this->container))->register();
        (new CodegenerServiceProvider($this->container))->register();

        $this->assertInstanceOf(Writer::class, $this->container->make(Writer::class));
    }
}
