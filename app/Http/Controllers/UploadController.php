<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers\ReadExcelSheetInfo;
use Illuminate\Support\Facades\Log;
use App\Actions\Upload\CreateUpload;
use App\Helpers\ReadJsonSheetInfo;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\FileUploadInterface;
use App\Http\Requests\UploadFileRequest;

class UploadController extends Controller
{
    use ResponseTrait;

    /**
     * 
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $files = Upload::whereBelongsTo($user)->with('user:id,forename,surname')->orderBy('updated_at', $request->sortAsc ? 'asc' : 'desc')->get();
        $data = ['message' => 'Files retrieved', 'data' => $files];
        return $this->returnJson(true, $data, 200);
    }

    /**
     * 
     */
    public function store(UploadFileRequest $request, FileUploadInterface $fileUpload, CreateUpload $createUpload): JsonResponse
    {
        $files = $request->safe()->uploads;

        $filesStoredToDisk = [];
        $errors = [];
        foreach ($files as $file) {
            $extension = $file->extension();
            $filePath = explode('.', $file->hashName())[0] . '.' . $extension;

            $sheetMetaData = in_array($extension, ['ods', 'xlsx', 'csv'])
                ? new ReadExcelSheetInfo($file, $extension, 0)
                : new ReadJsonSheetInfo($file, $extension);

            $metaData = $sheetMetaData->headings()->totalRows()->totalCols();

            $params = [
                'client_name' => $file->getClientOriginalName(),
                'path' => $filePath,
                'extension' => $extension,
                'user_id' => Auth::id(),
                'meta_data' => json_encode($metaData->meta),
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

    /**
     * 
     */
    public function destroy(Upload $upload, FileUploadInterface $fileUploadInterface): JsonResponse
    {
        DB::beginTransaction();

        try {
            $storagePath = $upload->path;
            $response = $upload->delete();

            if (!$response) {
                throw new Exception("An error occured in deleting the uplaod from the DB");
            }

            $response = $fileUploadInterface->delete($storagePath);

            if (!$response) {
                throw new Exception("An error occured in deleting the upload from storage");
            }
        } catch (Exception $e) {
            Log::info(json_encode($e));
            DB::rollback();
            return $this->returnJson(false, [
                'message' => 'An error occured. If this persists, please contact our team.'
            ], 422);
        }

        DB::commit();

        $data = ['message' => 'File removed'];

        return $this->returnJson(true, $data, 203);
    }
}
