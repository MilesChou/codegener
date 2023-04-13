<?php

declare(strict_types=1);

namespace MilesChou\Codegener\Traits;

trait Path
{
    /**
     * @var string
     */
    private string $basePath;

    /**
     * @param string $path
     * @return static
     */
    public function appendBasePath(string $path): static
    {
        $this->basePath = $this->formatPath(trim($path, '/'));

        return $this;
    }

    /**
     * Get base path or get location at running command
     *
     * @return string
     */
    public function basePath(): string
    {
        return $this->basePath ?? (string)getcwd();
    }

    /**
     * @param string $path
     * @return string
     */
    public function formatPath(string $path): string
    {
        $path = (string)$path;

        if ('' === $path) {
            return $this->basePath();
        }

        // if $path is absolute path, do nothing
        if (str_starts_with($path, '/')) {
            return $path;
        }

        return $this->basePath() . '/' . $path;
    }

    /**
     * @param string $basePath
     * @return static
     */
    public function setBasePath(string $basePath): static
    {
        $this->basePath = rtrim($basePath, '/');

        return $this;
    }
}
