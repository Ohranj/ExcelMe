<?php

namespace App\Services\Uploads;

use App\Interfaces\FileUploadInterface;

class FileUploadLocal implements FileUploadInterface
{
    public function upload(): string
    {
        return 'upload path Local';
    }
}
