<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomGuard implements StatefulGuard
{
    public $user = null;
    public $provider;
    public $session;
    public $name;

    private $prefix = '38gfre9';

    public function __construct($provider, $session, $name)
    {
        $this->provider = $provider;
        $this->session = $session;
        $this->name = $name;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool  $remember
     */
    public function attempt(array $credentials = [], $remember = false): bool
    {
        $validation = $this->validate($credentials);
        if (!$validation['state']) {
            return false;
        }

        $this->login($validation['user']);

        $rememberMe = $remember ?? false;

        if ($rememberMe) {
            $this->setRememberMe();
        }

        return true;
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array  $credentials
     */
    public function once(array $credentials = []): bool
    {
        dd('to do - once');
        return false;
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     */
    public function login(Authenticatable $user, $remember = false): void
    {
        $this->session->put($this->prefix . 'user_id', $user->id);
        $this->session->put($this->prefix . 'via_remember_me', false);
        $this->user = $user;
        event(new Login($this->name, $this->user, $remember));
        $this->session->regenerate();
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed  $id
     * @param  bool  $remember
     * @return \Illuminate\Contracts\Auth\Authenticatable|bool
     */
    public function loginUsingId($id, $remember = false)
    {
        $user = User::find($id);
        $this->session->put($this->prefix . 'user_id', $user->id);
        $this->session->put($this->prefix . 'via_remember_me', true);
        $this->user = $user;
        event(new Login($this->name, $this->user, true));
        return true;
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed  $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|bool
     */
    public function onceUsingId($id)
    {
        dd('to do - onceusingid');
        return false;
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     */
    public function viaRemember(): bool
    {
        return $this->session->get($this->prefix . 'via_remember_me', fn () => false);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(): void
    {
        $hasRememberMe = $this->hasValidRememberMeCookie();
        if ($hasRememberMe['state']) {
            Cookie::queue(Cookie::forget(config('app.name') . '_remember_me'));
        }
        $this->session->invalidate();
        $this->session->regenerateToken();
        $this->user = null;
    }

    /**
     * Determine if the current user is authenticated.
     */
    public function check(): bool
    {
        return $this->user() != null;
    }

    /**
     * Determine if the current user is a guest.
     */
    public function guest(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|false
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $id = $this->session->get($this->prefix . 'user_id', fn () => false);

        if ($id) {
            $this->user = $this->provider->retrieveById($id);
            return $this->user;
        }

        return false;
    }

    /**
     * Get the ID for the currently authenticated user.
     */
    public function id(): int|string|null
    {
        $user = $this->user();
        return $user
            ? $user->id
            : null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     */
    public function validate(array $credentials = []): array
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        if (!$user) {
            return ['state' => false, 'user' => null];
        }

        $isValid = Hash::check($credentials['password'], $user->password);

        if (!$isValid) {
            return  ['state' => false, 'user' => null];
        }

        return ['state' => true, 'user' => $user];
    }

    /**
     * Determine if the guard has a user instance.
     */
    public function hasUser(): bool
    {
        return $this->user ? true : false;
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function setUser(Authenticatable $user): void
    {
        dd('to do - setUser');
        return;
    }

    /**
     * 
     */
    private function setRememberMe(): void
    {
        $token = Str::random(16);
        $duration = 60 * 24 * 365;
        $name = config('app.name') . '_remember_me';
        Cookie::queue($name, $token, $duration);

        /** @var User $user */
        $user = $this->user();
        $user->remember_token = $token;
        $user->save();
    }

    /**
     * 
     */
    public function hasValidRememberMeCookie(): array
    {
        $rememberCookie = Cookie::get(config('app.name') . '_remember_me', false);
        if ($rememberCookie) {
            $user = User::where('remember_token', $rememberCookie)->first();
            if ($user) {
                return ['user' => $user, 'state' => true];
            }
        }
        return ['user' => null, 'state' => false];
    }
}
