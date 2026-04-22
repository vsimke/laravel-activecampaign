<?php

declare(strict_types=1);

namespace Vsimke\ActiveCampaign\Requests;

/**
 * BulkCreateContactsRequest class
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class BulkCreateContactsRequest extends CreateContactRequest
{
    /**
     * The params to be sent with the callback request.
     *
     * @var array<int,array<string,mixed>>
     */
    protected array $params = [];

    /**
     * The mapping of custom field slugs to ActiveCampaign field IDs.
     *
     * @var array<string,int>
     */
    protected array $customFields = [];

    /**
     * The callback URL for import results.
     */
    protected ?string $callbackUrl = null;

    /**
     * Initialize a new BulkCreateContactsRequest instance.
     *
     * @param  BulkCreateContactRequest[]  $requests
     */
    public function __construct(protected array $requests = []) {}

    /**
     * Map the custom field values to the ActiveCampaign field IDs.
     *
     * @param  array<string,int>  $customFields
     */
    public function setCustomFields(array $customFields): static
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * Set the callback URL for bulk import results.
     */
    public function setCallbackUrl(string $url): static
    {
        $this->callbackUrl = $url;

        return $this;
    }

    /**
     * Add a contact request to the bulk create request.
     */
    public function addRequest(BulkCreateContactRequest $request): static
    {
        $this->requests[] = $request;

        return $this;
    }

    /**
     * Add a parameter to the callback request.
     */
    public function addParam(string $key, mixed $value): static
    {
        $this->params[] = [
            'key'   => $key,
            'value' => $value,
        ];

        return $this;
    }

    /**
     * Convert the request to an array.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $callback = $this->callbackUrl ? array_filter([
            'url'              => $this->callbackUrl,
            'requestType'      => 'POST',
            'detailed_results' => true,
            'params'           => $this->params,
        ]) : null;

        return array_filter([
            'contacts'            => collect($this->requests)->map(fn (BulkCreateContactRequest $request): array => $request->setCustomFields($this->customFields)->toArray())->all(),
            'callback'            => $callback,
            'exclude_automations' => true,
        ]);
    }
}
