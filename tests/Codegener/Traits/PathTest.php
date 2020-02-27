<?php

namespace Tests\Codegener\Traits;

use MilesChou\Codegener\Traits\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /**
     * @var Path
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = $this->getMockForTrait(Path::class);
    }

    protected function tearDown(): void
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldReturnBasePathWhenCallFormatPathWithEmptyString(): void
    {
        $this->target->setBasePath('/a/b/c');

        $this->assertSame('/a/b/c', $this->target->formatPath(''));
    }

    /**
     * @test
     */
    public function shouldReturnPathWhenCallFormatPathWithAbsolutePath(): void
    {
        $this->target->setBasePath('/a/b/c');

        $this->assertSame('/d/e/f', $this->target->formatPath('/d/e/f'));
    }

    /**
     * @test
     */
    public function shouldReturnCombinePathWhenCallFormatPathWithNormalPath(): void
    {
        $this->target->setBasePath('/a/b/c');

        $this->assertSame('/a/b/c/d/e/f', $this->target->formatPath('d/e/f'));
    }

    /**
     * @test
     */
    public function shouldReturnAppendedPathWhenCallAppendBasePathWithNormalPath(): void
    {
        $this->target->setBasePath('/a/b/c');
        $this->target->appendBasePath('d/e/f');

        $this->assertSame('/a/b/c/d/e/f', $this->target->basePath());
    }

    /**
     * @test
     */
    public function shouldReturnAppendedPathWhenCallAppendBasePathWithAbsolutePath(): void
    {
        $this->target->setBasePath('/a/b/c');
        $this->target->appendBasePath('/d/e/f');

        $this->assertSame('/a/b/c/d/e/f', $this->target->basePath());
    }

    /**
     * @test
     */
    public function shouldReturnNewInstanceWhenWithMethod(): void
    {
        $this->target->setBasePath('/a/b/c');
        $this->target->appendBasePath('/d/e/f');

        $clone = $this->target->withBasePath('/i/j/k')
            ->withAppendBasePath('/x/y/z');

        $this->assertSame('/a/b/c/d/e/f', $this->target->basePath());
        $this->assertSame('/i/j/k/x/y/z', $clone->basePath());
    }
}
