<?php

namespace App\Listeners\Commands;

use Illuminate\Support\Facades\App;
use App\Interfaces\FileUploadInterface;
use Illuminate\Console\Events\CommandFinished;

class RemoveStorageFilesOnMigrateRefresh
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommandFinished $command): void
    {
        if ($command->command == 'migrate:fresh') {
            $fileProvider = App::make(FileUploadInterface::class);
            $fileProvider->deleteAllFiles();
        }
    }
}
