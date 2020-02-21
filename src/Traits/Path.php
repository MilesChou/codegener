<?php

declare(strict_types=1);

namespace MilesChou\Codegener\Traits;

trait Path
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * Get base path or get location at running command
     *
     * @return string
     */
    public function basePath(): string
    {
        return $this->basePath ?? getcwd();
    }

    /**
     * Inject base path if need test
     *
     * @param string $basePath
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function normalizePath($path): string
    {
        // if $path is absolute path, do nothing
        if (strpos($path, '/') === 0) {
            return $path;
        }

        return $this->basePath() . '/' . $path;
    }
}
