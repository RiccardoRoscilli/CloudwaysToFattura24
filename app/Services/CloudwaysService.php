<?php

namespace App\Services;

use GuzzleHttp\Client;

class CloudwaysService
{
    protected $client;
    protected $apiUrl = 'https://api.cloudways.com/api/v1';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
        ]);
    }

    // Ottieni il token di autenticazione da Cloudways
    public function authenticate()
    {

        try {
            // Effettua la richiesta POST per ottenere il token OAuth
            $response = $this->client->post($this->apiUrl . '/oauth/access_token', [
                'form_params' => [
                    'email' => env('CLOUDWAYS_EMAIL'),
                    'api_key' => env('CLOUDWAYS_API_KEY'),
                ]
            ]);

            // Decodifica il corpo della risposta
            $body = json_decode($response->getBody(), true);

            // Verifica se il token è presente nella risposta
            if (isset($body['access_token'])) {
                return $body['access_token'];
            } else {
                // Stampa la risposta per comprendere eventuali problemi
                return $body; // Potresti loggare il corpo per ulteriori debug
            }

        } catch (\Exception $e) {
            // Gestisci gli errori di eccezione
            return $e->getMessage(); // Logga o visualizza il messaggio di errore
        }
    }

    // Recupera i servizi dall'API Cloudways
    public function getServersWithApplications($accessToken)
    {
        try {
            $response = $this->client->get('https://api.cloudways.com/api/v1/server', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
