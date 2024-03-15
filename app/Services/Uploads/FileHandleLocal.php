<?php

namespace App\Services\Uploads;

use App\Interfaces\FileUploadInterface;
use Illuminate\Support\Facades\Storage;

class FileHandleLocal implements FileUploadInterface
{
    public function store($path, $file): bool
    {
        return Storage::put($path, file_get_contents($file));
    }

    public function delete($path): bool
    {
        return Storage::delete($path);
    }

    public function downloadFile($upload)
    {
        return Storage::download($upload->path, $upload->client_name);
    }
}
