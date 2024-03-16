<?php

namespace App\Interfaces;

interface FileUploadInterface
{
    public function store($path, $file): bool;
    public function delete($path): bool;
    public function downloadFile($upload);
    public function deleteAllFiles(): void;
}
