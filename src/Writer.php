<?php

namespace MilesChou\Codegener;

use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
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
     * @param LoggerInterface $logger
     */
    public function __construct(Filesystem $filesystem, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * @param string $path
     * @param string $content
     * @param bool $skipWhenExists
     */
    public function write(string $path, $content, bool $skipWhenExists = false): void
    {
        if ($skipWhenExists && $this->filesystem->exists($path)) {
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
}
