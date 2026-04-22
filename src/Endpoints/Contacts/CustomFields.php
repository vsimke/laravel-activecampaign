<?php

namespace Vsimke\ActiveCampaign\Endpoints\Contacts;

use GuzzleHttp\Client;
use Vsimke\ActiveCampaign\Endpoints\UseHttpRequest;
use Vsimke\ActiveCampaign\Requests\CreateCustomFieldRequest;
use Vsimke\ActiveCampaign\Requests\UpdateCustomFieldRequest;

/**
 * CustomFields class
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class CustomFields
{
    use UseHttpRequest;

    /**
     * Instantiate a new instance.
     */
    public function __construct(protected Client $client) {}

    /**
     * Create a new custom field in ActiveCampaign.
     *
     * @return array<string, mixed>
     */
    public function create(CreateCustomFieldRequest $request): array
    {
        $input = $request->toArray();

        $response = $this->post('/api/3/fields', $input);

        return $response['field'] ?? [];
    }

    /**
     * Update a custom field in ActiveCampaign.
     *
     * @return array<string, mixed>
     */
    public function update(int $id, UpdateCustomFieldRequest $request): array
    {
        $input = $request->toArray();

        $response = $this->put("/api/3/fields/{$id}", $input);

        return $response['field'] ?? [];
    }

    /**
     * Remove a custom field in ActiveCampaign.
     */
    public function remove(int $id): bool
    {
        $this->delete("/api/3/fields/{$id}");

        return true;
    }

    /**
     * Get the custom fields from ActiveCampaign.
     *
     * @return array<string, mixed>
     */
    public function list(int $limit = 100): array
    {
        $response = $this->get('/api/3/fields', [
            'limit' => $limit,
        ]);

        return $response['fields'] ?? [];
    }

    /**
     * Create a custom field relationship to list(s)
     *
     * @return array<string,mixed>
     */
    public function relationship(int $id, int $listId): array
    {
        $response = $this->post('/api/3/fieldRels', [
            'fieldRel' => [
                'field' => $id,
                'relid' => $listId,
            ],
        ]);

        return $response['fieldRel'] ?? [];
    }
}
