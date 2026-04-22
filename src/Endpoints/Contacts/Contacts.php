<?php

namespace Vsimke\ActiveCampaign\Endpoints\Contacts;

use Vsimke\ActiveCampaign\Endpoints\UseHttpRequest;
use Vsimke\ActiveCampaign\Requests\BulkCreateContactsRequest;
use Vsimke\ActiveCampaign\Requests\CreateContactRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Contacts class
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class Contacts
{
    use UseHttpRequest;

    /**
     * The configuration.
     *
     * @var Collection<string,mixed>
     */
    protected Collection $config;

    /**
     * Instantiate a new instance.
     *
     * @param  array<string,int>  $customFields
     * @param  array<string,mixed>  $config
     */
    public function __construct(
        protected Client $client,
        protected array $customFields = [],
        array $config = []
    ) {
        $this->config = collect($config);
    }

    /**
     * Get the custom fields.
     */
    public function customFields(): CustomFields
    {
        return new CustomFields($this->client);
    }

    /**
     * Get the tags.
     */
    public function tags(): Tags
    {
        return new Tags($this->client, $this->config);
    }

    /**
     * Find a contact by email address.
     *
     * @return array<string, mixed>
     */
    public function find(string $email): array
    {
        $response = $this->get('/api/3/contacts', [
            'email' => $email,
        ]);

        return $response['contacts'][0] ?? [];
    }

    /**
     * Create or update a new contact in ActiveCampaign.
     *
     * @return array<string, mixed>
     */
    public function updateOrCreate(CreateContactRequest $request): array
    {
        $request->setCustomFields($this->customFields);

        $response = $this->post('/api/3/contact/sync', $request->toArray());

        return $response['contact'] ?? [];
    }

    /**
     * Update an existing contact in ActiveCampaign.
     *
     * @return array<string, mixed>
     */
    public function update(int $id, CreateContactRequest $request): array
    {
        $request->setCustomFields($this->customFields);

        $response = $this->put("/api/3/contacts/{$id}", $request->toArray());

        return $response['contact'] ?? [];
    }

    /**
     * Remove a contact from ActiveCampaign.
     *
     * @return array<string, mixed>
     */
    public function remove(int $id): array
    {
        $response = $this->delete("/api/3/contacts/{$id}");

        return $response['contact'] ?? [];
    }

    /**
     * Bulk update or create contacts in ActiveCampaign.
     */
    public function bulkUpdateOrCreate(BulkCreateContactsRequest $request): ?string
    {
        $request->setCustomFields($this->customFields);

        $response = $this->post('/api/3/import/bulk_import', $request->toArray());

        return $response['batchId'] ?? null;
    }

    /**
     * Add a contact to a list.
     *
     * @return array<string, mixed>
     */
    public function addToList(int $contactId, string $list): array
    {
        $listId = $this->getListId($list);

        $response = $this->post('/api/3/contactLists', [
            'contactList' => [
                'list'    => $listId,
                'contact' => $contactId,
                'status'  => 1,
            ],
        ]);

        return $response['contactList'] ?? [];
    }

    /**
     * Get List ID from the configuration.
     */
    protected function getListId(string $list): int
    {
        $listId = collect((array) $this->config->get('lists', []))->where('slug', $list)->value('id');

        if (! $listId) {
            throw new InvalidArgumentException("List '$list' not found in ActiveCampaign configuration.");
        }

        return $listId;
    }
}
