<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Vsimke\ActiveCampaign\Endpoints\Contacts\Contacts;
use Vsimke\ActiveCampaign\Requests\CreateContactRequest;

function mockContacts(array $responses, array $config = []): Contacts
{
    $mock = new MockHandler($responses);
    $client = new Client(['handler' => HandlerStack::create($mock)]);

    return new Contacts($client, [], $config);
}

it('finds a contact by email', function (): void {
    $body = json_encode([
        'contacts' => [
            ['id' => '1', 'email' => 'john@example.com', 'firstName' => 'John'],
        ],
    ]);

    $contacts = mockContacts([new Response(200, [], $body)]);

    $contact = $contacts->find('john@example.com');

    expect($contact['id'])->toBe('1');
    expect($contact['email'])->toBe('john@example.com');
});

it('returns an empty array when contact is not found', function (): void {
    $body = json_encode(['contacts' => []]);

    $contacts = mockContacts([new Response(200, [], $body)]);

    expect($contacts->find('nobody@example.com'))->toBe([]);
});

it('creates or updates a contact via sync', function (): void {
    $body = json_encode([
        'contact' => ['id' => '5', 'email' => 'new@example.com'],
    ]);

    $contacts = mockContacts([new Response(200, [], $body)]);

    $request = (new CreateContactRequest)->setEmail('new@example.com');

    $contact = $contacts->updateOrCreate($request);

    expect($contact['id'])->toBe('5');
    expect($contact['email'])->toBe('new@example.com');
});

it('adds a contact to a list', function (): void {
    $body = json_encode([
        'contactList' => ['id' => '10', 'contact' => '5', 'list' => '1'],
    ]);

    $contacts = mockContacts(
        [new Response(200, [], $body)],
        ['lists' => [['slug' => 'newsletter', 'id' => 1]]]
    );

    $result = $contacts->addToList(5, 'newsletter');

    expect($result['list'])->toBe('1');
});

it('throws when adding a contact to an unknown list', function (): void {
    $contacts = mockContacts([], ['lists' => []]);

    $contacts->addToList(5, 'nonexistent');
})->throws(InvalidArgumentException::class);
