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
        return $this->basePath ?? ((string)getcwd());
    }

    /**
     * @param string $path
     * @return string
     */
    public function formatPath($path): string
    {
        // if $path is absolute path, do nothing
        if (strpos($path, '/') === 0) {
            return $path;
        }

        return $this->basePath() . '/' . $path;
    }

    /**
     * @param string $basePath
     * @return static
     */
    public function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }
}
