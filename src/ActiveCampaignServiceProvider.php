<?php

namespace Vsimke\ActiveCampaign;

use Vsimke\ActiveCampaign\Requests\BulkCreateContactRequest;
use Illuminate\Support\ServiceProvider;

class ActiveCampaignServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/activecampaign.php',
            'activecampaign'
        );

        $this->app->singleton(ActiveCampaign::class, function (array $app): \Vsimke\ActiveCampaign\ActiveCampaign {
            $config = $app['config']['activecampaign'];

            return new ActiveCampaign(
                url: $config['url'] ?? null,
                key: $config['key'] ?? null,
                config: $config,
            );
        });

        $this->app->bind(BulkCreateContactRequest::class, fn($app): \Vsimke\ActiveCampaign\Requests\BulkCreateContactRequest => new BulkCreateContactRequest(
            config: $app['config']['activecampaign']
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/activecampaign.php' => config_path('activecampaign.php'),
            ], 'activecampaign-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'activecampaign-migrations');
        }
    }
}
