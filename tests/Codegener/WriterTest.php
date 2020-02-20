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
    public function shouldSkipWhenOverwriteIsFalse(): void
    {
        $this->target->write($this->vfs->url() . '/dir/whatever', 'something');
        $this->target->write($this->vfs->url() . '/dir/whatever', 'new-something', false);

        $this->assertSame('something', file_get_contents($this->vfs->url() . '/dir/whatever'));
    }

    /**
     * @test
     */
    public function shouldOverwriteWhenFileExist(): void
    {
        $this->target->write($this->vfs->url() . '/dir/whatever', 'something');

        $this->target->overwrite($this->vfs->url() . '/dir/whatever', 'anotherthing');

        $this->assertSame('anotherthing', file_get_contents($this->vfs->url() . '/dir/whatever'));
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
    public function shouldOverwriteUseOverwriteMass(): void
    {
        $this->target->writeMass([
            $this->vfs->url() . '/dir/some-foo' => 'foo',
            $this->vfs->url() . '/dir/some-bar' => 'bar',
        ]);

        $this->target->overwriteMass([
            $this->vfs->url() . '/dir/some-foo' => 'new-foo',
            $this->vfs->url() . '/dir/some-bar' => 'new-bar',
        ]);

        $this->assertSame('new-foo', file_get_contents($this->vfs->url() . '/dir/some-foo'));
        $this->assertSame('new-bar', file_get_contents($this->vfs->url() . '/dir/some-bar'));
    }

    /**
     * @test
     */
    public function shouldSkipWhenFileExists(): void
    {
        $this->target->writeMass([
            $this->vfs->url() . '/dir/some-foo' => 'foo',
            $this->vfs->url() . '/dir/some-bar' => 'bar',
        ]);

        $this->target->writeMass([
            $this->vfs->url() . '/dir/some-foo' => 'new-foo',
            $this->vfs->url() . '/dir/some-bar' => 'new-bar',
            $this->vfs->url() . '/dir/some-baz' => 'new-baz',
        ]);

        $this->assertSame('foo', file_get_contents($this->vfs->url() . '/dir/some-foo'));
        $this->assertSame('bar', file_get_contents($this->vfs->url() . '/dir/some-bar'));
        $this->assertSame('new-baz', file_get_contents($this->vfs->url() . '/dir/some-baz'));
    }
}
