<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\FileUploadInterface;

class UploadController extends Controller
{
    use ResponseTrait;

    public function store(Request $request, FileUploadInterface $fileUpload)
    {
        //Validate


        $fileInserts = [];
        $errors = [];
        foreach ($request->uploads as $file) {
            $params = [
                'name' => explode('.', $file->hashName())[0],
                'extension' => $file->extension(),
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ];
            $params['path'] = $fileUpload->upload();
            $fileInserts[] = $params;
        }


        Upload::insert($fileInserts);




        //Add DB transaction - If 1 fails, fail all
        $data = [
            'message' => 'Files uploaded'
        ];

        return $this->returnJson(true, $data, 201);
    }
}
