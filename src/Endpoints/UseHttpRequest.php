<?php

namespace Vsimke\ActiveCampaign\Endpoints;

use GuzzleHttp\Utils;
use GuzzleHttp\Exception\ClientException;
use Vsimke\ActiveCampaign\Exceptions\ActiveCampaignException;

trait UseHttpRequest
{
    /**
     * Send a GET request to the ActiveCampaign API.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function get(string $endpoint, array $params = []): array
    {
        try {
            $response = $this->client->get($endpoint, [
                'query' => $params,
            ]);
        } catch (ClientException $e) {
            throw new ActiveCampaignException($e);
        }

        return (array) Utils::jsonDecode($response->getBody(), true);
    }

    /**
     * Send a POST request to the ActiveCampaign API.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->post($endpoint, [
                'json' => $data,
            ]);
        } catch (ClientException $e) {
            throw new ActiveCampaignException($e);
        }

        return (array) Utils::jsonDecode($response->getBody(), true);
    }

    /**
     * Send a PUT request to the ActiveCampaign API.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function put(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->put($endpoint, [
                'json' => $data,
            ]);
        } catch (ClientException $e) {
            throw new ActiveCampaignException($e);
        }

        return (array) Utils::jsonDecode($response->getBody(), true);
    }

    /**
     * Send a DELETE request to the ActiveCampaign API.
     *
     * @return array<string, mixed>
     */
    public function delete(string $endpoint): array
    {
        try {
            $response = $this->client->delete($endpoint);
        } catch (ClientException $e) {
            throw new ActiveCampaignException($e);
        }

        $body = (string) $response->getBody();

        return $body !== '' ? (array) Utils::jsonDecode($body, true) : [];
    }
}
