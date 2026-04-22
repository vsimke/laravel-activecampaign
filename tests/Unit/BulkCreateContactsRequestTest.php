<?php

declare(strict_types=1);

use Vsimke\ActiveCampaign\Requests\BulkCreateContactRequest;
use Vsimke\ActiveCampaign\Requests\BulkCreateContactsRequest;

$config = [
    'tags'  => [
        ['slug' => 'new-lead', 'name' => 'New Lead', 'id' => 10],
    ],
    'lists' => [
        ['slug' => 'newsletter', 'id' => 1],
    ],
];

it('converts a collection of contact requests to the bulk import array', function () use ($config): void {
    $contact1 = (new BulkCreateContactRequest($config))
        ->setEmail('alice@example.com')
        ->setFirstName('Alice');

    $contact2 = (new BulkCreateContactRequest($config))
        ->setEmail('bob@example.com')
        ->setFirstName('Bob');

    $bulk = (new BulkCreateContactsRequest([$contact1, $contact2]))
        ->setCustomFields([]);

    $result = $bulk->toArray();

    expect($result['contacts'])->toHaveCount(2);
    expect($result['contacts'][0]['email'])->toBe('alice@example.com');
    expect($result['contacts'][1]['email'])->toBe('bob@example.com');
    expect($result['exclude_automations'])->toBeTrue();
});

it('includes a callback when a callback URL is set', function () use ($config): void {
    $contact = (new BulkCreateContactRequest($config))
        ->setEmail('carol@example.com');

    $bulk = (new BulkCreateContactsRequest([$contact]))
        ->setCustomFields([])
        ->setCallbackUrl('https://example.com/webhook')
        ->addParam('batch_id', 'abc-123');

    $result = $bulk->toArray();

    expect($result['callback']['url'])->toBe('https://example.com/webhook');
    expect($result['callback']['requestType'])->toBe('POST');
    expect($result['callback']['params'])->toEqual([['key' => 'batch_id', 'value' => 'abc-123']]);
});

it('omits callback when no callback URL is set', function () use ($config): void {
    $contact = (new BulkCreateContactRequest($config))->setEmail('dave@example.com');

    $bulk = (new BulkCreateContactsRequest([$contact]))->setCustomFields([]);

    $result = $bulk->toArray();

    expect($result)->not->toHaveKey('callback');
});

it('resolves tag names and list ids from config in BulkCreateContactRequest', function () use ($config): void {
    $contact = (new BulkCreateContactRequest($config))
        ->setEmail('eve@example.com')
        ->addTag('new-lead')
        ->addToList('newsletter');

    $result = $contact->toArray();

    expect($result['tags'])->toContain('New Lead');
    expect($result['subscribe'])->toContain(['listid' => 1]);
});
