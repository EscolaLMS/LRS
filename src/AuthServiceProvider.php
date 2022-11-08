<?php

namespace EscolaLms\Lrs;

use EscolaLms\Lrs\Extensions\AccessTokenGuard;
use EscolaLms\Lrs\Models\Statement;
use EscolaLms\Lrs\Policies\StatementPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Statement::class => StatementPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        if (! $this->app->routesAreCached() && method_exists(Passport::class, 'routes')) {
            Passport::routes();
        }
        Passport::loadKeysFrom(__DIR__ . '/../../storage');

        Auth::extend('access_token', function () {
            $request = app('request');
            return new AccessTokenGuard($request);
        });
    }
}
