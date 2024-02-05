<?php

namespace App\Providers;

use App\Services\CustomGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::extend('customGuard', function (Application $app, string $name, array $config) {
            $userProvider = Auth::createUserProvider($config['provider']);
            $session = $app['session'];
            return new CustomGuard($userProvider, $session, $name);
        });
    }
}
