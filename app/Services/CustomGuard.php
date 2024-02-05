<?php

namespace App\Services;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Hash;
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
        dd('to do - loginusingid');
        return false;
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
        dd('to do - viaremember');
        return false;
    }

    /**
     * Log the user out of the application.
     */
    public function logout(): void
    {
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
        dd('to do - hasUser');
        return false;
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
}
