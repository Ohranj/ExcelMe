<?php

namespace App\Services\Uploads;

use App\Interfaces\FileUploadInterface;
use Illuminate\Support\Facades\Storage;

class FileHandleAWS implements FileUploadInterface
{
    public function store($path, $file): bool
    {
        return false;
    }

    public function delete($path): bool
    {
        return false;
    }

    public function downloadJson($upload)
    {
        //
    }
}
