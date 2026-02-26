<?php

namespace App\Services;

use GuzzleHttp\Client;

class FirebaseService
{
    protected Client $client;
    protected string $projectId;
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        // Load service account JSON
        $json = json_decode(file_get_contents(config('firebase.credentials')), true);

        $this->projectId = $json['project_id'];

        // Get OAuth token
        $this->token = $this->getAccessToken($json);

        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
        $this->client = new Client();
    }

    // Get OAuth access token from service account JSON
    protected function getAccessToken(array $json): string
    {
        $jwt = \Firebase\JWT\JWT::encode([
            'iss' => $json['client_email'],
            'scope' => 'https://www.googleapis.com/auth/datastore',
            'aud' => $json['token_uri'],
            'iat' => time(),
            'exp' => time() + 3600,
        ], $json['private_key'], 'RS256');

        $client = new Client();
        $response = $client->post($json['token_uri'], [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'];
    }

    // Create / update document
    public function setDocument(string $collection, string $document, array $data)
    {
        $url = "{$this->baseUrl}/{$collection}/{$document}";

        return $this->client->patch($url, [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'fields' => $this->encodeFirestoreData($data),
            ],
        ]);
    }

    protected function encodeFirestoreData(array $data): array
    {
        $formatted = [];

        foreach ($data as $key => $value) {
            $formatted[$key] = ['stringValue' => (string)$value];
        }

        return $formatted;
    }
}
