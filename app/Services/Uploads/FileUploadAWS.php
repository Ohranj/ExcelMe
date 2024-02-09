<?php

namespace App\Services\Uploads;

use App\Interfaces\FileUploadInterface;

class FileUploadAWS implements FileUploadInterface
{
    public function upload(): string
    {
        return 'upload path AWS';
    }
}
