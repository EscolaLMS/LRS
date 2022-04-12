<?php

namespace EscolaLms\Lrs;

use EscolaLms\Lrs\Repositories\Contracts\StatementRepositoryContract;
use EscolaLms\Lrs\Repositories\StatementRepository;
use EscolaLms\Lrs\Services\Contracts\StatementServiceContract;
use EscolaLms\Lrs\Services\StatementService;
use Illuminate\Support\ServiceProvider;
use EscolaLms\Lrs\Services\Contracts\LrsServiceContract;
use EscolaLms\Lrs\Services\LrsService;
use Illuminate\Support\Facades\Config;
use EscolaLms\Lrs\Extensions\AccessTokenGuard;
use \Trax\Core\TraxCoreServiceProvider;
use \Trax\Auth\AuthServiceProvider as TraxAuthServiceProvider;
use \Trax\XapiValidation\XapiValidationServiceProvider;
use \Trax\XapiStore\XapiStoreServiceProvider;

/**
 * SWAGGER_VERSION
 */
class EscolaLmsLrsServiceProvider extends ServiceProvider
{
    public $singletons = [
        LrsServiceContract::class => LrsService::class,
        StatementServiceContract::class => StatementService::class,
        StatementRepositoryContract::class => StatementRepository::class,
    ];

    private $requiredProviders = [
        TraxCoreServiceProvider::class,
        TraxAuthServiceProvider::class,
        XapiValidationServiceProvider::class,
        XapiStoreServiceProvider::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->requiredProviders as $provider) {
            if (!app()->bound($provider)) {
                $this->app->register($provider);
            }
        }

        Config::set(
            'trax-auth.app.guards.basic_http',
            AccessTokenGuard::class
        );

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
    }
}
