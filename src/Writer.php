<?php

declare(strict_types=1);

namespace MilesChou\Codegener;

use Illuminate\Filesystem\Filesystem;
use MilesChou\Codegener\Traits\Path;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

class Writer
{
    use Path;

    private Filesystem $filesystem;

    private LoggerInterface $logger;

    /**
     * @param Filesystem $filesystem
     * @param LoggerInterface|null $logger
     */
    public function __construct(Filesystem $filesystem, LoggerInterface $logger = null)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param string $path
     * @param string $content
     * @param bool $overwrite
     */
    public function write(string $path, string $content, bool $overwrite = false): void
    {
        $path = $this->formatPath($path);

        if (!$overwrite && $this->filesystem->exists($path)) {
            $this->logger->info("File '{$path}' exists, skip");
            return;
        }

        $dir = $this->filesystem->dirname($path);

        if (!$this->filesystem->isDirectory($dir)) {
            $this->filesystem->makeDirectory($dir, 0755, true, true);
        }

        $this->logger->info("Write code into file '{$path}'");
        $this->logger->debug("code content: \n---\n{$content}\n---");

        $success = $this->filesystem->put($path, $content);

        if (false === $success) {
            throw new RuntimeException("Write code into {$path} failed.");
        }
    }

    /**
     * @param iterable<string> $contents Array which should return array like [path => code]
     * @param bool $overwrite
     */
    public function writeMass(iterable $contents, bool $overwrite = false): void
    {
        foreach ($contents as $path => $code) {
            $this->write($path, $code, $overwrite);
        }
    }

    /**
     * @param iterable<string> $contents
     */
    public function overwriteMass(iterable $contents): void
    {
        $this->writeMass($contents, true);
    }

    public function overwrite(string $path, string $content): void
    {
        $this->write($path, $content, true);
    }

    /**
     * Clone new instance with append base path
     *
     * @param string $basePath
     * @return static
     */
    public function withAppendBasePath(string $basePath): Writer
    {
        $clone = clone $this;

        return $clone->appendBasePath($basePath);
    }

    /**
     * Clone new instance with base path
     *
     * @param string $basePath
     * @return static
     */
    public function withBasePath(string $basePath): Writer
    {
        $clone = clone $this;

        return $clone->setBasePath($basePath);
    }
}
