<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Application;
use App\Models\Service;
use App\Models\Mailbox;
use App\Models\User;


use Illuminate\Http\Request;
use App\Http\Controllers\DataTableController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
    public function edit($id)
    {
        // $order = Order::with(['items.application', 'items.service', 'customer'])->findOrFail($id);
        $order = Order::with(['customer', 'items.mailbox', 'items.service', 'items.application'])->findOrFail($id);

        return view('orders.edit', compact('order'));
    }

    // Funzione per aggiornare un ordine
    public function updatePrice(Request $request, $itemId)
    {

        try {
            // Trova l'OrderItem tramite l'ID
            $item = OrderItem::findOrFail($itemId);

            // Trova l'ordine associato
            $order = $item->order;

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Ordine non trovato.'], 404);
            }

            // Aggiorna il prezzo dell'OrderItem
            $item->price = $request->input('price');
            $item->save();

            // Ricarica gli OrderItem per evitare dati obsoleti
            $orderItems = OrderItem::where('order_id', $order->id)->get();


            if ($request->has('status')) {
                $order->status = $request->input('status');
                $order->save();
            }

            // Ricalcola il totale dell'ordine

            $totalAmount = 0;

            foreach ($orderItems as $orderItem) {

                $quantity = ($orderItem->billing_frequency === 'quarterly') ? 3 : 1;
                $totalAmount += ($orderItem->price * $quantity);


            }

            $order->amount = $totalAmount;
            $order->save();

            // Risposta JSON di successo
            return response()->json([
                'success' => true,
                'message' => 'Prezzo aggiornato con successo!',
                'newTotal' => number_format($order->amount, 2),
            ]);
        } catch (\Exception $e) {
            // Log dell'errore e risposta JSON
            \Log::error('Errore durante l\'aggiornamento del prezzo:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Errore durante l\'aggiornamento del prezzo.'], 500);
        }
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

        // Definisci la query con le relazioni tra applications e customers
        $query = Order::query()
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*', 'customers.name');

        // Chiama il metodo generico del DataTableController
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
        try {
            // Recupera l'ordine e i dettagli del cliente
            $order = Order::with('customer', 'items')->findOrFail($orderId);

            $admin = User::where('role', 'admin')->first();
            if (!$admin || !$admin->configuration->fattura24_api_key) {
                throw new \Exception('API Key per l\'utente admin non trovata.');
            }
            $fattura24ApiKey = $admin->configuration->fattura24_api_key;

            if (!$fattura24ApiKey) {
                return response()->json(['success' => false, 'message' => 'API Key per Fattura24 non configurata.'], 400);
            }

            $customer = $order->customer;

            // Calcolo dei totali
            $totalWithoutTax = 0;
            foreach ($order->items as $item) {
                $quantity = $item->billing_frequency === 'quarterly' ? 3 : 1;
                $totalWithoutTax += $item->price * $quantity;
            }
            $vatAmount = $totalWithoutTax * 0.22; // Calcolo IVA (22%)
            $totalWithTax = $totalWithoutTax + $vatAmount; // Totale IVA inclusa
            $paymentDate = \Carbon\Carbon::now()->format('Y-m-d');

            // Creazione XML
            $xml = new \SimpleXMLElement('<?xml version="1.0"?><Fattura24></Fattura24>');
            $document = $xml->addChild('Document');

            // Dettagli del cliente
            $document->addChild('DocumentType', 'C');
            $document->addChild('CustomerName', $this->escapeXmlValue($customer->name ?? 'N/A'));
            $document->addChild('CustomerAddress', $customer->billing_street . ' ' . $customer->billing_civic_number ?? 'N/A');
            $document->addChild('CustomerPostcode', $customer->billing_postal_code ?? 'N/A');
            $document->addChild('CustomerCity', $customer->billing_city ?? 'N/A');
            $document->addChild('CustomerProvince', $customer->billing_province ?? 'N/A');
            $document->addChild('CustomerCountry', $customer->billing_country ?? 'N/A');
            $document->addChild('CustomerFiscalCode', $customer->fiscal_code ?? 'N/A');
            $document->addChild('CustomerVatCode', $customer->vat_number ?? 'N/A');
            $document->addChild('CustomerCellPhone', $customer->mobile ?? $customer->phone ?? 'N/A');
            $document->addChild('CustomerEmail', $customer->email ?? 'N/A');

            // Dettagli di consegna
            $document->addChild('DeliveryName', $this->escapeXmlValue($customer->name ?? 'N/A'));
            $document->addChild('DeliveryAddress', $customer->billing_street . ' ' . $customer->billing_civic_number ?? 'N/A');
            $document->addChild('DeliveryPostcode', $customer->billing_postal_code ?? 'N/A');
            $document->addChild('DeliveryCity', $customer->billing_city ?? 'N/A');
            $document->addChild('DeliveryProvince', $customer->billing_province ?? 'N/A');
            $document->addChild('DeliveryCountry', $customer->billing_country ?? 'N/A');

            // Metodo di pagamento
            $document->addChild('PaymentMethodName', 'Fineco Bank, conto intestato a Roscilli Riccardo');
            $document->addChild('PaymentMethodDescription', 'IT60C0301503200000003248252');

            // Totali
            $document->addChild('Object', 'Abbonamento hosting e servizi');
            $document->addChild('TotalWithoutTax', number_format($totalWithoutTax, 2, '.', ''));
            $document->addChild('VatAmount', number_format($vatAmount, 2, '.', ''));
            $document->addChild('Total', number_format($totalWithTax, 2, '.', ''));
            $document->addChild('SendEmail', 'false');

            // Pagamenti
            $payments = $document->addChild('Payments');
            $payment = $payments->addChild('Payment');
            $payment->addChild('Date', $paymentDate);
            $payment->addChild('Amount', number_format($totalWithTax, 2, '.', ''));
            $payment->addChild('Paid', 'false');

            // Righe dell'ordine
            $rows = $document->addChild('Rows');
            foreach ($order->items as $item) {
                $row = $rows->addChild('Row');
                $row->addChild('Code', $item->id);
                $quantity = $item->billing_frequency === 'quarterly' ? 3 : 1;

                // Descrizione basata sul tipo di servizio
                if ($item->service_type === 'application') {
                    $application = Application::find($item->service_id);
                    $description = 'Hosting ' . ($application ? $application->label : 'N/A') . ' dal ' .
                        \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') . ' al ' .
                        \Carbon\Carbon::parse($item->end_date)->format('d/m/Y');
                } elseif ($item->service_type === 'service' || $item->service_type === 'dominio') {
                    $service = Service::find($item->service_id);
                    $description = $service->service_type === 'dominio'
                        ? 'Dominio: ' . $service->service_name . ' dal ' .
                        \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') . ' al ' .
                        \Carbon\Carbon::parse($item->end_date)->format('d/m/Y')
                        : ucfirst($service->service_type) . ' ' . $service->service_name . ' dal ' .
                        \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') . ' al ' .
                        \Carbon\Carbon::parse($item->end_date)->format('d/m/Y');
                } elseif ($item->service_type === 'mailbox') {
                    $mailbox = Mailbox::find($item->service_id);
                    $description = 'Mailbox: ' . ($mailbox ? $mailbox->email : 'N/A') . ' dal ' .
                        \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') . ' al ' .
                        \Carbon\Carbon::parse($item->end_date)->format('d/m/Y');
                } else {
                    $description = 'N/A';
                }

                $row->addChild('Description', $description);
                $row->addChild('Qty', $quantity);
                $row->addChild('Price', number_format($item->price, 2, '.', ''));
                $row->addChild('VatCode', 22);
                $row->addChild('VatDescription', '22%');
            }

            // Invio della richiesta
            $xmlString = $xml->asXML();

            // Formatta l'XML per il logging
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xmlString);
            $formattedXml = $dom->saveXML();

            // Log della richiesta in uscita con XML formattato
            \Log::channel('fattura24')->info("Richiesta inviata a Fattura24:\n" . $formattedXml, [
                'url' => 'https://www.app.fattura24.com/api/v0.3/SaveDocument',
                'method' => 'POST',
                'apiKey' => $fattura24ApiKey,
            ]);

            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://www.app.fattura24.com/api/v0.3/SaveDocument', [
                'form_params' => [
                    'apiKey' => $fattura24ApiKey,
                    'xml' => $xmlString,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseXml = simplexml_load_string($responseBody);

            // Controllo della risposta
            if ((string) $responseXml->returnCode === '0') {
                $order->update(['status' => 'sentF24']);
                return response()->json([
                    'success' => true,
                    'message' => 'Ordine inviato a Fattura24 con successo!',
                    'docId' => (string) $responseXml->docId,
                    'docNumber' => (string) $responseXml->docNumber,
                    'description' => (string) $responseXml->description,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante l\'invio a Fattura24: ' . (string) $responseXml->description,
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Errore durante l\'invio a Fattura24:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'invio: ' . $e->getMessage(),
            ], 500);
        }
    }

    function escapeXmlValue($value)
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    public function sendMail($orderId)
    {
        try {
            $order = Order::with('customer', 'items')->findOrFail($orderId);
            $customer = $order->customer;
    
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Cliente non trovato.'], 404);
            }
           
            // Invia la mail
            \Mail::to($customer->email)->send(new \App\Mail\PaymentInstructions($customer, $order));
    
            return response()->json(['success' => true, 'message' => 'Email inviata con successo!']);
        } catch (\Exception $e) {
            \Log::error('Errore durante l\'invio della mail:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Errore durante l\'invio della mail.'], 500);
        }
    }
    

    function arrayToXml($data, &$xmlData)
    {
        foreach ($data as $key => $value) {
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
