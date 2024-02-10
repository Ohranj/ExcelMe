<?php

namespace App\Providers;

use App\Interfaces\FileUploadInterface;
use App\Services\Uploads\FileHandleAWS;
use Illuminate\Support\ServiceProvider;
use App\Services\Uploads\FileHandleLocal;

class FileUploadApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FileUploadInterface::class, fn () =>
        match (config('filesystems.default')) {
            'private' => new FileHandleLocal(),
            's3' => new FileHandleAWS(),
            default => null
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
