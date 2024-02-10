<?php

namespace App\Http\Controllers;

use App\Actions\Upload\CreateUpload;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\FileUploadInterface;

class UploadController extends Controller
{
    use ResponseTrait;

    public function store(Request $request, FileUploadInterface $fileUpload, CreateUpload $createUpload)
    {
        //Validate size and type - move to request class


        $fileInserts = [];
        $errors = [];
        foreach ($request->uploads as $file) {
            $safeName =   $file->name = explode('.', $file->hashName())[0];
            $extension = $file->extension();
            $filePath = $safeName . '.' . $extension;

            $params = [
                'client_name' => $file->getClientOriginalName(),
                'path' => $filePath,
                'extension' => $extension,
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            $fileStored = $fileUpload->store($filePath, $file);

            if (!$fileStored) {
                array_push($errors, $params['client_name']);
                continue;
            }

            $fileInserts[] = $params;
        }

        $insertWasSuccess = $createUpload->execute($fileInserts);

        if (!$insertWasSuccess) {
            foreach ($fileInserts as $insert) {
                $fileUpload->delete($insert['path']);
            }

            $data = ['message' => 'An error occured on upload. Please check and try again.'];
            return $this->returnJson(false, $data, 422);
        }

        $data = ['message' => 'Files uploaded',  'errors' => $errors];
        return $this->returnJson(true, $data, 201);
    }
}
