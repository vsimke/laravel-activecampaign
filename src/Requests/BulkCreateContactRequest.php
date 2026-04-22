<?php

namespace Vsimke\ActiveCampaign\Requests;

use InvalidArgumentException;

/**
 * BulkCreateContactRequest class
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class BulkCreateContactRequest extends CreateContactRequest
{
    /**
     * Lists to which the contact will be added.
     *
     * @var array<int, array{listid: int}>
     */
    protected array $lists = [];

    /**
     * Tags
     *
     * @var string[]
     */
    protected array $tags = [];

    /**
     * Initialize a new instance.
     *
     * @param  array<string,mixed>  $config
     */
    public function __construct(
        /**
         * The activecampaign configuration array.
         */
        protected array $config = []
    )
    {
    }

    /**
     * Add a tag to the contact by slug.
     */
    public function addTag(string $tag): static
    {
        $name = collect((array) ($this->config['tags'] ?? []))->where('slug', $tag)->value('name');

        $this->tags[] = $name;

        return $this;
    }

    /**
     * Add the contact to a list by slug.
     */
    public function addToList(string $list): static
    {
        $id = (int) collect((array) ($this->config['lists'] ?? []))->where('slug', $list)->value('id');

        $this->lists[] = ['listid' => $id];

        return $this;
    }

    /**
     * Get the contact data as an array for the bulk import API.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $fieldValues = collect($this->fieldValues)->map(function ($value, $perstag): array {
            if ($this->customFields[$perstag] ?? false) {
                return [
                    'id'    => (int) $this->customFields[$perstag],
                    'value' => $value,
                ];
            }

            throw new InvalidArgumentException("Custom field perstag {$perstag} not found in mapping.");
        })
            ->toArray();

        return array_filter([
            'email'      => $this->email,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'phone'      => $this->phone,
            'fields'     => array_values($fieldValues),
            'subscribe'  => $this->lists,
            'tags'       => $this->tags,
        ]);
    }
}
