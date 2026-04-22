<?php

namespace Vsimke\ActiveCampaign;

use GuzzleHttp\Client;
use InvalidArgumentException;
use Vsimke\ActiveCampaign\Models\CustomField;
use Vsimke\ActiveCampaign\Endpoints\Contacts\Contacts;

/**
 * ActiveCampaign class.
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class ActiveCampaign
{
    /**
     * The Guzzle HTTP client instance.
     */
    protected Client $client;

    /**
     * The mapping of custom fields (perstag => field_id).
     *
     * @var array<string,int>
     */
    protected array $customFields = [];

    /**
     * Create a new ActiveCampaign instance.
     *
     * @param  array<string,mixed>  $config  Full activecampaign config (lists, tags, etc.)
     */
    public function __construct(
        protected ?string $url,
        protected ?string $key,
        protected array $config = []
    ) {
        $this->url = rtrim((string) $this->url, '/');

        $this->client = new Client([
            'base_uri' => $this->url,
            'headers'  => [
                'Api-Token'    => $this->key,
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Get the ActiveCampaign contacts API instance.
     */
    public function contacts(): Contacts
    {
        if (! $this->isConfigured()) {
            throw new InvalidArgumentException('ActiveCampaign is not configured.');
        }

        return new Contacts($this->client, $this->getCustomFields(), $this->config);
    }

    /**
     * Determine whether the instance is properly configured.
     */
    public function isConfigured(): bool
    {
        return ! in_array($this->url, [null, '', '0'], true) && ! in_array($this->key, [null, '', '0'], true);
    }

    /**
     * Get the custom fields mapping (perstag => field_id).
     *
     * @return array<string,int>
     */
    protected function getCustomFields(): array
    {
        if ($this->customFields === []) {
            $this->customFields = CustomField::pluck('field_id', 'perstag')->toArray();
        }

        return $this->customFields;
    }
}
