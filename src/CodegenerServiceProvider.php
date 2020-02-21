<?php

declare(strict_types=1);

namespace MilesChou\Codegener;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Psr\Log\LoggerInterface;

class CodegenerServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->singleton(Writer::class, function () {
            return new Writer($this->resolveFilesystem(), $this->resolveLogger());
        });
    }

    private function resolveFilesystem(): Filesystem
    {
        if ($this->app->bound('files')) {
            return $this->app->make('files');
        }

        return $this->app->make(Filesystem::class);
    }

    private function resolveLogger(): ?LoggerInterface
    {
        if ($this->app->bound('log')) {
            return $this->app->make('log');
        }

        if ($this->app->bound(LoggerInterface::class)) {
            return $this->app->make(LoggerInterface::class);
        }

        return null;
    }
}
