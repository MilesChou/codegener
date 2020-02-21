<?php

declare(strict_types=1);

namespace MilesChou\Codegener\Traits;

use Dotenv\Dotenv;

trait Environment
{
    /**
     * @param string $filename
     * @see https://github.com/vlucas/phpdotenv
     */
    protected function loadEnv($filename): void
    {
        if (class_exists(Dotenv::class) && is_file($filename)) {
            (Dotenv::create(
                dirname($filename),
                basename($filename)
            ))->load();
        }
    }
}
