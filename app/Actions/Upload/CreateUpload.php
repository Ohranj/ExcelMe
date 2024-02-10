<?php

namespace App\Actions\Upload;

use App\Models\Upload;

class CreateUpload
{
    public function execute(array $files): bool
    {
        return Upload::insert($files);
    }
}
