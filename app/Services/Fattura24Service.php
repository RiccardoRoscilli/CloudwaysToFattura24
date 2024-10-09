<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Customer;

class Fattura24Service
{
    protected $client;

    public function __construct()
    {
        // Imposta l'endpoint corretto
        $this->client = new Client([
            'base_uri' => 'https://www.app.fattura24.com/api/v0.3/',
        ]);
    }

    public function importCustomers()
    {
        try {
            // Esegui la richiesta all'API
            $response = $this->client->post('TestKey', [
                'form_params' => [
                    'apiKey' => env('FATTURA24_API_KEY'),
                ],
            ]);

            // Ottieni il corpo della risposta
            $body = $response->getBody()->getContents();

            // Verifica se la risposta è in XML
            $xml = simplexml_load_string($body);

            if ($xml === false) {
                // Se c'è un errore nel parsing XML, visualizza l'errore
                dd('Errore nel parsing XML: ', libxml_get_errors());
            }

            // Converte l'oggetto XML in array per facilitare l'accesso ai dati
            $json = json_encode($xml);
            $array = json_decode($json, true);
            dd($array);
            // Ora possiamo accedere ai dati dei clienti e importarli
            // Nota: Modifica questo in base alla struttura della risposta reale dei clienti
            foreach ($array['customers'] as $customer) {
                Customer::updateOrCreate([
                    'email' => $customer['email'],
                ], [
                    'name' => $customer['name'],
                    'fiscal_code' => $customer['fiscal_code'],
                    'vat_number' => $customer['vat_number'],
                    'billing_street' => $customer['billing_street'],
                    'billing_city' => $customer['billing_city'],
                    'billing_postal_code' => $customer['billing_postal_code'],
                    'billing_province' => $customer['billing_province'],
                    'billing_country' => $customer['billing_country'],
                    'sdi_code' => $customer['sdi_code'],
                    'pec_email' => $customer['pec_email'],
                ]);
            }

            return 'Clienti importati con successo';

        } catch (\Exception $e) {
            dd('Errore durante l\'importazione dei clienti: ', $e->getMessage());
        }
    }
}
