<?php

use Vsimke\ActiveCampaign\Requests\CreateContactRequest;

it('builds a minimal contact array', function (): void {
    $request = (new CreateContactRequest)
        ->setEmail('john@example.com')
        ->setFirstName('John')
        ->setLastName('Doe');

    $result = $request->toArray();

    expect($result)->toMatchArray([
        'contact' => [
            'email'     => 'john@example.com',
            'firstName' => 'John',
            'lastName'  => 'Doe',
        ],
    ]);
});

it('maps custom field values using the perstag to field_id mapping', function (): void {
    $request = (new CreateContactRequest)
        ->setEmail('jane@example.com')
        ->setFirstName('Jane')
        ->setCustomFields(['COUNTRY' => 42, 'USERNAME' => 99])
        ->setFieldValue('COUNTRY', 'Switzerland')
        ->setFieldValue('USERNAME', 'jane.doe');

    $result = $request->toArray();

    expect($result['contact']['fieldValues'])->toEqual([
        ['field' => 42, 'value' => 'Switzerland'],
        ['field' => 99, 'value' => 'jane.doe'],
    ]);
});

it('throws when a perstag is not in the custom fields mapping', function (): void {
    $request = (new CreateContactRequest)
        ->setEmail('x@example.com')
        ->setCustomFields([])
        ->setFieldValue('MISSING_PERSTAG', 'value');

    $request->toArray();
})->throws(InvalidArgumentException::class, 'Custom field perstag MISSING_PERSTAG not found in mapping.');

it('omits empty fields from the contact array', function (): void {
    $request = (new CreateContactRequest)->setEmail('only@example.com');

    $result = $request->toArray();

    expect($result['contact'])->not->toHaveKey('firstName');
    expect($result['contact'])->not->toHaveKey('lastName');
    expect($result['contact'])->not->toHaveKey('phone');
});
