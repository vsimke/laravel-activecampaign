<?php

namespace Vsimke\ActiveCampaign\Requests;

use InvalidArgumentException;

/**
 * Create Contact Request
 *
 * Represents a contact request in ActiveCampaign.
 *
 * @phpstan-consistent-constructor
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class CreateContactRequest
{
    /**
     * The email address of the contact.
     */
    protected string $email = '';

    /**
     * The first name of the contact.
     */
    protected string $firstName = '';

    /**
     * The last name of the contact.
     */
    protected string $lastName = '';

    /**
     * The phone number of the contact.
     */
    protected string $phone = '';

    /**
     * The custom field values for the contact.
     *
     * @var array<string, string>
     */
    protected array $fieldValues = [];

    /**
     * The mapping of custom field slugs to ActiveCampaign field IDs.
     *
     * @var array<string,int>
     */
    protected array $customFields = [];

    /**
     * Set the email address of the contact.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the first name of the contact.
     */
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Set the last name of the contact.
     */
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Set the phone number of the contact.
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Set a custom field value for the contact.
     *
     * @param  string  $perstag  The custom field perstag identifier.
     */
    public function setFieldValue(string $perstag, string $value): static
    {
        $this->fieldValues[$perstag] = $value;

        return $this;
    }

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
     * Get the contact data as an array for the ActiveCampaign API.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $fieldValues = collect($this->fieldValues)->map(function ($value, $perstag): array {
            if ($this->customFields[$perstag] ?? false) {
                return [
                    'field' => $this->customFields[$perstag],
                    'value' => $value,
                ];
            }

            throw new InvalidArgumentException("Custom field perstag {$perstag} not found in mapping.");
        })
            ->toArray();

        return [
            'contact' => array_filter([
                'email'       => $this->email,
                'firstName'   => $this->firstName,
                'lastName'    => $this->lastName,
                'phone'       => $this->phone,
                'fieldValues' => array_values($fieldValues),
            ]),
        ];
    }
}
