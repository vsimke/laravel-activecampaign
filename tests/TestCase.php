<?php

namespace Vsimke\ActiveCampaign\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vsimke\ActiveCampaign\ActiveCampaignServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ActiveCampaignServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('activecampaign', [
            'url' => 'https://test.api-us1.com',
            'key' => 'test-api-key',
            'lists' => [
                ['slug' => 'newsletter', 'id' => 1],
                ['slug' => 'affiliates', 'id' => 2],
            ],
            'tags' => [
                ['slug' => 'new-lead', 'name' => 'New Lead', 'id' => 10],
                ['slug' => 'converted', 'name' => 'Converted', 'id' => 11],
            ],
        ]);
    }
}
