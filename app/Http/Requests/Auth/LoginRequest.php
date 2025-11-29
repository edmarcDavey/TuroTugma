<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            // Login now accepts a single admin ID (field named "id") and password
            'id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Only allow the single configured admin ID to attempt authentication.
        $adminId = env('ADMIN_ID', '300627-101');
        $inputId = (string) $this->string('id');


        if ($inputId !== $adminId) {
            RateLimiter::hit($this->throttleKey());
            // rejected because input ID did not match configured admin ID
            throw ValidationException::withMessages([
                'id' => trans('auth.failed'),
            ]);
        }

        // Attempt authentication using the admin's stored email field (the admin ID is stored in `email`).
        $passwordPlain = (string) $this->string('password');
        $attempt = Auth::attempt(['email' => $inputId, 'password' => $passwordPlain], $this->boolean('remember'));
        if (! $attempt) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'id' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('id')).'|'.$this->ip());
    }
}
