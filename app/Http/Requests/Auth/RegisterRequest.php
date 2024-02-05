<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
            'forename' => ['required', 'string', 'max:200'],
            'surname' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'email', 'max:200', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'create_organisation' => ['sometimes', 'boolean'],
            'organisation' => ['required_if:create_organisation,true', 'string', 'nullable', 'max:200']
        ];
    }

    protected function passedValidation(): void
    {
        $this->replace([
            ...$this->safe()->only(['forename', 'surname', 'email']),
            'password' => Hash::make($this->validated()['password']),
        ]);
    }

    public function shouldSetOrganisation(): bool
    {
        return $this->validated()['create_organisation'] ?? false;
    }
}
