<?php

declare(strict_types=1);

namespace Vsimke\ActiveCampaign\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Vsimke\ActiveCampaign\Endpoints\Contacts\Contacts contacts()
 * @method static bool isConfigured()
 *
 * @see \Vsimke\ActiveCampaign\ActiveCampaign
 */
class ActiveCampaign extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Vsimke\ActiveCampaign\ActiveCampaign::class;
    }
}
