<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    private $attemptsBeforeThrottle = 5;

    /**
     *
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     *
     */
    public function checkIsThrottled(): array
    {
        $key = $this->throttleKey();
        if (RateLimiter::tooManyAttempts($key, $this->attemptsBeforeThrottle)) {
            event(new Lockout($this));
            $seconds = RateLimiter::availableIn($key);
            $message = 'Too many attemps. Please wait ' . $seconds . ' seconds before trying again.';
            return ['state' => true, 'message' => $message];
        }

        return ['state' => false, 'message' => null];
    }

    /**
     *
     */
    public function hitThrottleKey(): void
    {
        RateLimiter::hit($this->throttleKey());
    }

    /**
     * Retrieve the throttle key
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . $this->ip());
    }

    /**
     *
     */
    public function clearThrottleKey(): void
    {
        RateLimiter::clear($this->throttleKey());
    }
}
