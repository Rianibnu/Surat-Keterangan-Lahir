<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Teramedik\TeramedikClient;
use App\Services\Teramedik\MockTeramedikService;
use App\Services\Teramedik\PatientService;
use App\Services\Teramedik\DoctorService;
use App\Services\Teramedik\EncounterService;
use App\Services\Teramedik\BirthRecordSyncService;

class TeramedikServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind TeramedikClient as singleton
        $this->app->singleton(TeramedikClient::class, function ($app) {
            return new TeramedikClient();
        });

        // Bind MockTeramedikService as singleton
        $this->app->singleton(MockTeramedikService::class, function ($app) {
            return new MockTeramedikService();
        });

        // Bind domain services
        $this->app->singleton(PatientService::class, function ($app) {
            return new PatientService($app->make(TeramedikClient::class));
        });

        $this->app->singleton(DoctorService::class, function ($app) {
            return new DoctorService($app->make(TeramedikClient::class));
        });

        $this->app->singleton(EncounterService::class, function ($app) {
            return new EncounterService($app->make(TeramedikClient::class));
        });

        $this->app->singleton(BirthRecordSyncService::class, function ($app) {
            return new BirthRecordSyncService($app->make(TeramedikClient::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config jika dibutuhkan
        $this->publishes([
            __DIR__.'/../../config/teramedik.php' => config_path('teramedik.php'),
        ], 'teramedik-config');
    }
}
