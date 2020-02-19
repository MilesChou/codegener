<?php

namespace Tests\Codegener;

use Illuminate\Filesystem\Filesystem;
use MilesChou\Codegener\Writer;
use Psr\Log\NullLogger;
use Tests\TestCase;

class WriterTest extends TestCase
{
    /**
     * @var Writer
     */
    private $target;

    protected function setUp(): void
    {
        parent::setUp();

        $this->target = new Writer(new Filesystem(), new NullLogger());
    }

    protected function tearDown(): void
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenWriteNormalCode(): void
    {
        $this->target->write($this->vfs->url() . '/dir/whatever', 'something');

        $this->assertSame('something', file_get_contents($this->vfs->url() . '/dir/whatever'));
    }

    /**
     * @test
     */
    public function shouldSkipWhenFileExist(): void
    {
        $this->target->write($this->vfs->url() . '/dir/whatever', 'something');

        $this->target->writeOrSkip($this->vfs->url() . '/dir/whatever', 'anotherthing');

        $this->assertSame('something', file_get_contents($this->vfs->url() . '/dir/whatever'));
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenMassFileAccess(): void
    {
        $this->target->writeMass([
            $this->vfs->url() . '/dir/some-foo' => 'foo',
            $this->vfs->url() . '/dir/some-bar' => 'bar',
        ]);

        $this->assertSame('foo', file_get_contents($this->vfs->url() . '/dir/some-foo'));
        $this->assertSame('bar', file_get_contents($this->vfs->url() . '/dir/some-bar'));
    }

    /**
     * @test
     */
    public function shouldSkipWhenMassFileAccessAndFileExist(): void
    {
        $this->target->writeMass([
            $this->vfs->url() . '/dir/some-foo' => 'foo',
            $this->vfs->url() . '/dir/some-bar' => 'bar',
        ]);

        $this->target->writeMassOrPass([
            $this->vfs->url() . '/dir/some-foo' => 'new-foo',
            $this->vfs->url() . '/dir/some-bar' => 'new-bar',
            $this->vfs->url() . '/dir/some-baz' => 'new-baz',
        ]);

        $this->assertSame('foo', file_get_contents($this->vfs->url() . '/dir/some-foo'));
        $this->assertSame('bar', file_get_contents($this->vfs->url() . '/dir/some-bar'));
        $this->assertSame('new-baz', file_get_contents($this->vfs->url() . '/dir/some-baz'));
    }
}
