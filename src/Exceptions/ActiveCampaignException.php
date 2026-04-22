<?php

namespace Vsimke\ActiveCampaign\Exceptions;

use GuzzleHttp\Utils;
use GuzzleHttp\Exception\ClientException;

/**
 * Active Campaign Exception
 *
 * This exception is thrown when an error occurs while making a request to the ActiveCampaign API.
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class ActiveCampaignException extends \Exception
{
    /**
     * Create a new ActiveCampaignException instance.
     */
    public function __construct(ClientException $e)
    {
        $response = (array) Utils::jsonDecode($e->getResponse()->getBody(), true);

        parent::__construct(($response['errors'][0]['detail'] ?? 'Unknown error'), $e->getCode(), $e);
    }
}
