<?php

namespace Vsimke\ActiveCampaign\Requests;

/**
 * Create Custom Field Request
 *
 * Represents a custom field request in ActiveCampaign.
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class CreateCustomFieldRequest
{
    /**
     * The name of the custom field.
     */
    protected string $title;

    /**
     * The type of the custom field.
     */
    protected string $type;

    /**
     * The perstag of the custom field.
     */
    protected string $perstag;

    /**
     * Instantiate a new instance.
     *
     * @param  array<string,mixed>  $input
     */
    public function __construct(array $input)
    {
        $this->title = $input['title'] ?? '';
        $this->type = $input['type'] ?? 'text';
        $this->perstag = $input['perstag'] ?? '';
    }

    /**
     * Set the title of the custom field.
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the type of the custom field.
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the perstag of the custom field.
     */
    public function setPerstag(string $perstag): static
    {
        $this->perstag = $perstag;

        return $this;
    }

    /**
     * Convert the request to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'field' => [
                'title'   => $this->title,
                'type'    => $this->type,
                'perstag' => $this->perstag,
                'visible' => 1,
            ],
        ];
    }
}
