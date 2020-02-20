<?php

namespace MilesChou\Codegener;

use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

class Writer
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

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
    public function write(string $path, $content, bool $overwrite = false): void
    {
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
     * @param iterable $contents Array which should return array like [filePath => code]
     * @param string $pathPrefix
     * @param bool $overwrite
     */
    public function writeMass(iterable $contents, $pathPrefix = '', bool $overwrite = false): void
    {
        foreach ($contents as $filePath => $code) {
            $path = $pathPrefix . $filePath;

            $this->write($path, $code, $overwrite);
        }
    }

    /**
     * @param iterable $contents
     * @param string $pathPrefix
     */
    public function overwriteMass(iterable $contents, $pathPrefix = ''): void
    {
        $this->writeMass($contents, $pathPrefix, true);
    }

    public function overwrite(string $path, $content): void
    {
        $this->write($path, $content, true);
    }
}
