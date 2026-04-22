<?php

namespace Vsimke\ActiveCampaign;

use Illuminate\Support\ServiceProvider;
use Vsimke\ActiveCampaign\Requests\BulkCreateContactRequest;

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

        $this->app->singleton(ActiveCampaign::class, function (array $app): ActiveCampaign {
            $config = $app['config']['activecampaign'];

            return new ActiveCampaign(
                url: $config['url'] ?? null,
                key: $config['key'] ?? null,
                config: $config,
            );
        });

        $this->app->bind(BulkCreateContactRequest::class, fn ($app): BulkCreateContactRequest => new BulkCreateContactRequest(
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
