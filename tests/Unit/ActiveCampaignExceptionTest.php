<?php

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use Vsimke\ActiveCampaign\Exceptions\ActiveCampaignException;

it('extracts the error detail from the API response', function (): void {
    $body = json_encode([
        'errors' => [
            ['title' => 'Not Found', 'detail' => 'No Result found for Subscriber with id 999'],
        ],
    ]);

    $guzzleRequest = new Request('GET', '/api/3/contacts/999');
    $guzzleResponse = new Response(404, [], $body);

    $clientException = new ClientException('Client error', $guzzleRequest, $guzzleResponse);

    $exception = new ActiveCampaignException($clientException);

    expect($exception->getMessage())->toBe('No Result found for Subscriber with id 999');
    expect($exception->getCode())->toBe(404);
    expect($exception->getPrevious())->toBe($clientException);
});

it('falls back to "Unknown error" when the response has no errors', function (): void {
    $body = json_encode(['message' => 'Something went wrong']);

    $guzzleRequest = new Request('POST', '/api/3/contacts');
    $guzzleResponse = new Response(400, [], $body);

    $clientException = new ClientException('Client error', $guzzleRequest, $guzzleResponse);

    $exception = new ActiveCampaignException($clientException);

    expect($exception->getMessage())->toBe('Unknown error');
});
