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
        $this->target->setBasePath($this->vfs->url());
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
        $this->target->write('dir/whatever', 'something');

        $this->assertSame('something', file_get_contents($this->target->formatPath('dir/whatever')));
    }

    /**
     * @test
     */
    public function shouldSkipWhenOverwriteIsFalse(): void
    {
        $this->target->write('dir/whatever', 'something');
        $this->target->write('dir/whatever', 'new-something', false);

        $this->assertSame('something', file_get_contents($this->target->formatPath('dir/whatever')));
    }

    /**
     * @test
     */
    public function shouldOverwriteWhenFileExist(): void
    {
        $this->target->write('dir/whatever', 'something');

        $this->target->overwrite('dir/whatever', 'anotherthing');

        $this->assertSame('anotherthing', file_get_contents($this->target->formatPath('dir/whatever')));
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenMassFileAccess(): void
    {
        $this->target->writeMass([
            'dir/some-foo' => 'foo',
            'dir/some-bar' => 'bar',
        ]);

        $this->assertSame('foo', file_get_contents($this->target->formatPath('dir/some-foo')));
        $this->assertSame('bar', file_get_contents($this->target->formatPath('dir/some-bar')));
    }

    /**
     * @test
     */
    public function shouldOverwriteUseOverwriteMass(): void
    {
        $this->target->writeMass([
            'dir/some-foo' => 'foo',
            'dir/some-bar' => 'bar',
        ]);

        $this->target->overwriteMass([
            'dir/some-foo' => 'new-foo',
            'dir/some-bar' => 'new-bar',
        ]);

        $this->assertSame('new-foo', file_get_contents($this->target->formatPath('dir/some-foo')));
        $this->assertSame('new-bar', file_get_contents($this->target->formatPath('dir/some-bar')));
    }

    /**
     * @test
     */
    public function shouldSkipWhenFileExists(): void
    {
        $this->target->writeMass([
            'dir/some-foo' => 'foo',
            'dir/some-bar' => 'bar',
        ]);

        $this->target->writeMass([
            'dir/some-foo' => 'new-foo',
            'dir/some-bar' => 'new-bar',
            'dir/some-baz' => 'new-baz',
        ]);

        $this->assertSame('foo', file_get_contents($this->target->formatPath('dir/some-foo')));
        $this->assertSame('bar', file_get_contents($this->target->formatPath('dir/some-bar')));
        $this->assertSame('new-baz', file_get_contents($this->target->formatPath('dir/some-baz')));
    }
}
