<?php

namespace App\Http\Controllers\Exports;

use App\Models\Upload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\FileUploadInterface;

class ExportOriginalSheetController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, FileUploadInterface $fileUpload, Upload $upload)
    {
        return $fileUpload->downloadFile($upload);
    }
}
