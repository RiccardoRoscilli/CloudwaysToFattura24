<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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
            // Ottieni l'utente autenticato
            $userId = auth()->id();

            // Recupera la configurazione dell'utente dal database
            $configuration = \App\Models\Configuration::where('user_id', $userId)->first();

             // Controlla se la configurazione esiste e se i campi email e API key non sono vuoti
             if (!$configuration || empty($configuration->cloudways_email) || empty($configuration->cloudways_api_key)) {
              //  Log::error('Errore: Configurazione mancante o incompleta per l\'utente con ID: ' . $userId);
                return 'Errore: Configurazione non trovata o incompleta per l\'utente';
            }

            // Effettua la richiesta POST per ottenere il token OAuth usando i dati dal database
            $response = $this->client->post($this->apiUrl . '/oauth/access_token', [
                'form_params' => [
                    'email' => $configuration->cloudways_email, // Usa l'email salvata nel DB
                    'api_key' => $configuration->cloudways_api_key, // Usa l'API key salvata nel DB
                ]
            ]);

            // Decodifica il corpo della risposta
            $body = json_decode($response->getBody(), true);

          //   Log::info('Risposta dall\'API Cloudways:', $body); // Questo loggherà la risposta come array

            // Verifica se il token è presente nella risposta
            if (isset($body['access_token'])) {
                return $body['access_token'];
            } else {
                // Loggare il corpo per ulteriori debug in caso di errore
                return $body;
            }
        } catch (\Exception $e) {
            // Gestione degli errori
            return $e->getMessage(); // Logga o mostra il messaggio di errore
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

            $responseBody = json_decode($response->getBody(), true);

            // Logga la risposta dell'API
            Log::info('Cloudways API Response:', ['response' => $responseBody]);

            return $responseBody;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
