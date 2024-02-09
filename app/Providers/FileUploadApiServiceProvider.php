<?php

namespace App\Providers;

use App\Interfaces\FileUploadInterface;
use App\Services\Uploads\FileUploadAWS;
use Illuminate\Support\ServiceProvider;
use App\Services\Uploads\FileUploadLocal;

class FileUploadApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FileUploadInterface::class, fn () =>
        match (config('filesystems.default')) {
            'local' => new FileUploadLocal(),
            's3' => new FileUploadAWS(),
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
