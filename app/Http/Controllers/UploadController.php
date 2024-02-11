<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Traits\ResponseTrait;
use App\Http\Requests\UploadFile;
use Illuminate\Http\JsonResponse;
use App\Actions\Upload\CreateUpload;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\FileUploadInterface;

class UploadController extends Controller
{
    use ResponseTrait;

    /**
     * 
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $files = Upload::whereBelongsTo($user)->get();
        $data = ['message' => 'Files retrieved', 'data' => $files];
        return $this->returnJson(true, $data, 200);
    }

    public function store(UploadFile $request, FileUploadInterface $fileUpload, CreateUpload $createUpload): JsonResponse
    {
        $files = $request->safe()->uploads;

        $filesStoredToDisk = [];
        $errors = [];
        foreach ($files as $file) {
            $extension = $file->extension();
            $filePath = explode('.', $file->hashName())[0] . '.' . $extension;

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

            $filesStoredToDisk[] = $params;
        }

        $insertWasSuccess = $createUpload->execute($filesStoredToDisk);

        if (!$insertWasSuccess) {
            foreach ($filesStoredToDisk as $insert) {
                $fileUpload->delete($insert['path']);
            }
            $data = ['message' => 'An error occured on upload. Please check and try again.', 'errors' => null];
            return $this->returnJson(false, $data, 422);
        }

        $data = [
            'message' => 'Files uploaded',
            'errors' => [
                'files' => $errors,
                'message' => count($errors)
                    ? 'We failed to upload all of your files left. If this persists, please contact our team.'
                    : null
            ]
        ];

        return $this->returnJson(true, $data, 201);
    }
}
