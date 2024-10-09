<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\DataTableController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use SimpleXMLElement;




class OrderController extends Controller
{
    protected $dates = ['start_date', 'end_date', 'payment_date'];

    // Funzione per mostrare tutti gli ordini
    public function index()
    {
        $orders = Order::with('application.customer')->get();
        return view('orders.index', compact('orders'));
    }

    // Funzione per mostrare il form di creazione di un nuovo ordine
    public function create()
    {
        $applications = Application::all();
        return view('orders.edit', compact('applications'));
    }

    // Funzione per salvare un nuovo ordine
    public function store(Request $request)
    {
        // Validazione dei campi
        $validatedData = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'payment_type' => 'required|string',
            'payment_id' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'status' => 'required|string', // Aggiungi la validazione per lo status se necessario
        ]);

        // Creazione del nuovo ordine utilizzando solo i campi validati
        Order::create($validatedData);

        return redirect()->route('orders.index')->with('success', 'Ordine creato con successo!');
    }

    // Funzione per mostrare il form di modifica di un ordine
    public function edit(Order $order)
    {
        $applications = Application::all();
        return view('orders.edit', compact('order', 'applications'));
    }

    // Funzione per aggiornare un ordine
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'payment_type' => 'required|string',
            'payment_id' => 'nullable|string',
            'payment_date' => 'nullable|date'
        ]);

        $order->update($request->all());

        return redirect()->route('orders.index')->with('success', 'Ordine aggiornato con successo!');
    }

    // Funzione per eliminare un ordine
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Ordine eliminato con successo!');
    }

    public function getOrders(Request $request)
    {
        $dataTableController = new DataTableController();

        // Passa la query specifica per Orders con le relazioni
        $query = \App\Models\Order::query()
            ->leftJoin('applications', 'orders.application_id', '=', 'applications.id')
            ->leftJoin('customers', 'applications.customer_id', '=', 'customers.id')
            ->select('orders.*', 'applications.label', 'customers.name');

        // $query->where('applications.label', 'LIKE', '%m%');

        //  $query->orderBy('application_label');
        //   dd($query->toSql(), $query->getBindings());
        return $dataTableController->getDataTable($request, $query);
    }

    public function testApiKey()
    {
        $apiKey = env('FATTURA24_API_KEY'); // Assumendo che tu abbia configurato l'API Key nel file .env e nei config

        $response = Http::get('https://www.app.fattura24.com/api/v0.3/TestKey', [
            'apiKey' => $apiKey,
        ]);

        if ($response->successful()) {
            $data = simplexml_load_string($response->body());
            return response()->json([
                'returnCode' => (string) $data->returnCode,
                'description' => (string) $data->description,
            ]);
        } else {
            return response()->json([
                'error' => 'Errore nella verifica della chiave API.',
                'details' => $response->body()
            ], $response->status());
        }
    }
    public function sendToFattura24($orderId)
    {

        $order = Order::findOrFail($orderId);


        $apiKey = env('FATTURA24_API_KEY');
        $data = [

            'documentType' => 'C',  // Tipo ordine cliente
            'document' => [
                'clientName' => $order->customer->name,
                'clientEmail' => $order->customer->email,
                'object' => 'Abbonamento hosting',  // Oggetto del documento
                'rows' => [
                    [
                        'code' => $order->application->label,
                        'description' => 'Abbonamento hosting dal ' .
                            Carbon::parse($order->start_date)->format('d/m/Y') .
                            ' al ' .
                            Carbon::parse($order->end_date)->format('d/m/Y'),

                        'qty' => 1,
                        'price' => $order->amount,  // Prezzo unitario
                        'vatCode' => 22,  // Codice IVA
                        'vatDescription' => '22%',  // Descrizione IVA
                    ]
                ],
                'payments' => [
                    [
                        'date' => $order->payment_date ? $order->payment_date->format('Y-m-d') : 'N/A',
                        'amount' => $order->amount,
                        'paid' => false,
                    ]
                ],
            ],
        ];



        try {
            $xmlData = new SimpleXMLElement('<?xml version="1.0"?><Fattura24></Fattura24>');
            $this->arrayToXml($data, $xmlData);
            $xmlString = $xmlData->asXML();

            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://www.app.fattura24.com/api/v0.3/SaveDocument', [
                'apiKey' => $apiKey,
                'xml' => $xmlString
            ]);

            $xml = simplexml_load_string($response->getBody()->getContents());
            $returnCode = (string)$xml->returnCode;
            $description = (string)$xml->description;
            $docId = (string)$xml->docId;
            $docNumber = (string)$xml->docNumber;

            if ($returnCode == '0') {
                return response()->json([
                    'message' => 'Ordine inviato a Fattura24 con successo!',
                    'docId' => $docId,
                    'docNumber' => $docNumber,
                    'description' => $description
                ]);
            } else {
                return response()->json([
                    'message' => 'Errore durante l\'invio a Fattura24: ' . $description
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Errore durante l\'invio a Fattura24: ' . $e->getMessage()
            ], 500);
        }
    }
    function arrayToXml($data, &$xmlData) {
        foreach($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key; // gestisci i nodi numerici
                }
                $subnode = $xmlData->addChild("$key");
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
