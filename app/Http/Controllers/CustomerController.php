<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\Fattura24Service;
use App\Models\Application;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $fattura24Service;

    public function __construct(Fattura24Service $fattura24Service)
    {
        $this->middleware('auth'); // Assicura che l'utente sia autenticato
        $this->middleware('role:admin'); // Solo gli admin possono accedere a questo controller
        $this->fattura24Service = $fattura24Service;
    }

    public function importFromCsv(Request $request)
    {
        // Percorso del file CSV
        $path = $request->file('csv_file')->getRealPath();

        // Carica i dati dal CSV con il separatore corretto (;)
        $data = array_map(function ($row) {
            return str_getcsv($row, ';');
        }, file($path));

        // Estrai l'intestazione
        $header = array_shift($data);

        // Verifica e combina i dati
        $customers = [];
        foreach ($data as $row) {
            // Controlla se il numero di colonne corrisponde
            if (count($header) === count($row)) {
                $customer = array_combine($header, $row);

                // Filtra i clienti che hanno "Cod. Destinatario" diverso da "XXXXXX" e "0000000"
                if ($customer['Cod. Destinatario'] !== 'XXXXXXX' && $customer['Cod. Destinatario'] !== '0') {
                    $customers[] = $customer;
                }
            } else {
                // Salta o logga le righe con un numero di colonne errato
                continue;
            }
        }

        // Ora procedi con l'importazione dei clienti filtrati
        foreach ($customers as $customer) {
            // Logica di importazione nel database
            Customer::updateOrCreate([
                'vat_number' => $customer['P.IVA'],
            ], [
                'name' => $customer['Rag. Sociale'] ?? $customer['Riferimento'],
                'fiscal_code' => $customer['Cod. fiscale'],
                'vat_number' => $customer['P.IVA'],
                'billing_street' => $customer['Indirizzo'],
                'billing_city' => $customer['Città'],
                'billing_postal_code' => $customer['CAP'],
                'billing_province' => $customer['Provincia'],
                'billing_country' => $customer['Paese'],
                'sdi_code' => $customer['Cod. Destinatario'],
                'pec_email' => $customer['Pec'],
                'phone' => $customer['Telefono'],
                'mobile' => $customer['Cellulare'],
                'fax' => $customer['FAX'],
                'iban' => $customer['IBAN'],
                'website' => $customer['Web'],
                'note' => $customer['Nota'],
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Clienti importati con successo.');
    }





    // CRUD: Lista dei clienti
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }
    // app/Http/Controllers/CustomerController.php

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $applications = Application::whereNull('customer_id')->get(); // Applicazioni non ancora associate

        return view('customers.show', compact('customer', 'applications'));
    }

    public function addApplication(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $application = Application::findOrFail($request->application_id);

        // Associare l'application al customer
        $application->customer_id = $customer->id;
        $application->save();

        return redirect()->route('customers.show', $customerId)->with('success', 'Application associata con successo!');
    }

    // CRUD: Mostra il form per creare un nuovo cliente
    public function create()
    {
        return view('customers.edit');
    }

    // CRUD: Salva un nuovo cliente nel database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            // Aggiungi ulteriori validazioni qui
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Cliente creato con successo.');
    }

    // CRUD: Mostra il form per modificare un cliente esistente
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // CRUD: Aggiorna un cliente esistente nel database
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            // Aggiungi ulteriori validazioni qui
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Cliente aggiornato con successo.');
    }

    // CRUD: Elimina un cliente
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Cliente eliminato con successo.');
    }
    public function getCustomers(Request $request)
    {
        $dataTableController = new DataTableController();

        // Definisci una query semplice per recuperare solo i customers
        $query = \App\Models\Customer::query();

        // Chiama il metodo generico del DataTableController, senza join
        return $dataTableController->getDataTable($request, $query);
    }
}
