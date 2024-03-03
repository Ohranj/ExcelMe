<?php

namespace App\Http\Requests;

use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uploads' => ['required', 'array'],
            'uploads.*' => ['extensions:ods,xlsx,csv,json', 'max:512', function ($attribute, $value, $fail) {
                $name = $value->getClientOriginalName();
                $exists = Upload::whereBelongsTo(Auth::user())->where('client_name', $name)->exists();
                if ($exists) {
                    $fail('The file ' . $name . ' already exists.');
                }
            }]
        ];
    }

    /**
     * 
     */
    public function messages(): array
    {
        return [
            'uploads.required' => 'Please attach the file(s) you wish to upload.',
            'uploads.*.extensions' => 'Invalid file type. Files must be of type: ods, xlsx.'
        ];
    }
}
