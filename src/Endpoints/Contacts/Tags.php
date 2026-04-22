<?php

namespace Vsimke\ActiveCampaign\Endpoints\Contacts;

use Vsimke\ActiveCampaign\Endpoints\UseHttpRequest;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Tags class
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class Tags
{
    use UseHttpRequest;

    /**
     * Instantiate a new instance.
     *
     * @param  Collection<string,mixed>  $config
     */
    public function __construct(protected Client $client, protected Collection $config) {}

    /**
     * List all contact tags.
     *
     * @return array<string, mixed>
     */
    public function list(int $contactId): array
    {
        $response = $this->get("/api/3/contacts/$contactId/contactTags");

        return $response['contactTags'] ?? [];
    }

    /**
     * Find a specific contact tag by contact ID and tag slug.
     *
     * @return array<string,mixed>
     */
    public function find(int $contactId, string $tag): array
    {
        $tagId = $this->getTagId($tag);

        try {
            $tags = $this->list($contactId);
        } catch (Exception) {
            return [];
        }

        if ($tags === []) {
            return [];
        }

        return collect($tags)
            ->where('contact', $contactId)
            ->where('tag', $tagId)
            ->first() ?: [];
    }

    /**
     * Add a tag to a contact.
     *
     * @return array<string, mixed>
     */
    public function add(int $contactId, string $tag): array
    {
        $tagId = $this->getTagId($tag);

        $data = [
            'contactTag' => [
                'contact' => $contactId,
                'tag'     => $tagId,
            ],
        ];

        $response = $this->post('/api/3/contactTags', $data);

        return $response['contactTag'] ?? [];
    }

    /**
     * Remove a tag from a contact.
     */
    public function remove(int $contactId, string $tag): void
    {
        $contactTag = $this->find($contactId, $tag);

        if ($contactTag === []) {
            return;
        }

        $this->delete("/api/3/contactTags/{$contactTag['id']}");
    }

    /**
     * Get the tag ID from the configuration based on the tag slug.
     *
     * @throws InvalidArgumentException
     */
    protected function getTagId(string $tag): int
    {
        $tagId = collect((array) $this->config->get('tags', []))->where('slug', $tag)->value('id');

        if (is_null($tagId)) {
            throw new InvalidArgumentException("Tag '$tag' not found in configuration.");
        }

        return $tagId;
    }
}
