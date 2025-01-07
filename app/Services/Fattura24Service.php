<?php


namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Configuration; // Aggiungi questa linea

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

    public function testKey()
    {
        try {
            // Recupera l'utente autenticato
            $userId = auth()->id();  // Usa l'helper globale

            // Recupera la configurazione dell'utente dal database
            $configuration = Configuration::where('user_id', $userId)->first();

            // Verifica se la configurazione è presente e se ha la chiave API
            if (!$configuration || empty($configuration->fattura24_api_key)) {
                return 'Errore: Chiave API Fattura24 non trovata.';
            }

            // Effettua la richiesta di test per verificare l'API key
            $response = $this->client->post('TestKey', [
                'form_params' => [
                    'apiKey' => $configuration->fattura24_api_key, // Usa la chiave API salvata nel DB
                ],
            ]);

            // Ottieni il corpo della risposta
            $body = $response->getBody()->getContents();

            // Verifica se la risposta è in XML
            $xml = simplexml_load_string($body);

            if ($xml === false) {
                \Log::error('Errore nel parsing XML di TestKey: ', libxml_get_errors());
                return 'Errore nel parsing della risposta XML.';
            }

            // Estrai i campi returnCode e description
            $returnCode = (int) $xml->returnCode;
            $description = (string) $xml->description;

            // Controlla il valore di returnCode
            if ($returnCode === 1) {
                return "Test API Fattura24 riuscito: " . $description;
            } elseif ($returnCode === -1) {
                return "Errore: Chiave API non valida. " . $description;
            } else {
                return "Errore sconosciuto API Fattura24. Codice: " . $returnCode . " Descrizione: " . $description;
            }

        } catch (\Exception $e) {
            \Log::error('Errore durante il test dell\'API Fattura24: ' . $e->getMessage());
            return 'Errore durante il test dell\'API Fattura24: ' . $e->getMessage();
        }
    }

    public function sendOrder(Order $order, string $apiKey): array
    {
        $totalWithoutTax = 0;
        foreach ($order->items as $item) {
            $quantity = $item->billing_frequency === 'quarterly' ? 3 : 1;
            $totalWithoutTax += $item->price * $quantity;
        }
        $vatAmount = $totalWithoutTax * 0.22;
        $totalWithTax = $totalWithoutTax + $vatAmount;

        $xml = new SimpleXMLElement('<?xml version="1.0"?><Fattura24></Fattura24>');
        $document = $xml->addChild('Document');
        $document->addChild('CustomerName', htmlspecialchars($order->customer->name ?? 'N/A'));
        $document->addChild('TotalWithoutTax', number_format($totalWithoutTax, 2, '.', ''));
        $document->addChild('Total', number_format($totalWithTax, 2, '.', ''));

        $rows = $document->addChild('Rows');
        foreach ($order->items as $item) {
            $row = $rows->addChild('Row');
            $row->addChild('Description', htmlspecialchars($item->description ?? 'N/A'));
            $row->addChild('Price', number_format($item->price, 2, '.', ''));
            $row->addChild('Qty', $item->billing_frequency === 'quarterly' ? 3 : 1);
            $row->addChild('VatCode', '22');
        }

        $xmlString = $xml->asXML();

        $client = new Client();
        $response = $client->post('https://www.app.fattura24.com/api/v0.3/SaveDocument', [
            'form_params' => [
                'apiKey' => $apiKey,
                'xml' => $xmlString,
            ],
        ]);

        $responseXml = simplexml_load_string($response->getBody()->getContents());

        if ((string)$responseXml->returnCode === '0') {
            $order->update(['status' => 'sent']);
            return [
                'success' => true,
                'docId' => (string)$responseXml->docId,
                'docNumber' => (string)$responseXml->docNumber,
                'description' => (string)$responseXml->description,
            ];
        } else {
            return [
                'success' => false,
                'message' => (string)$responseXml->description,
            ];
        }
    }
}
