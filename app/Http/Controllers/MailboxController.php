<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mailbox;
use App\Models\Customer;

class MailboxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Restituisce la vista index per le mail boxes
        return view('mailboxes.index');
    }


    public function create()
    {
        $customers = Customer::all();
        return view('mailboxes.edit', compact('customers'));
    }

    public function edit($id)
    {
        $mailbox = Mailbox::findOrFail($id);
        $customers = Customer::all();
        return view('mailboxes.edit', compact('mailbox', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validazione dei dati
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'email' => 'required|email|unique:mailboxes,email',
            'server' => 'required|string|max:255',
            'IMAPport' => 'required|integer|min:1',
            'SMTPport' => 'required|integer|min:1',
        ]);

        // Creazione della nuova mailbox
        Mailbox::create($validatedData);

        // Reindirizzamento alla lista delle mailbox con un messaggio di successo
        return redirect()->route('mailboxes.index')->with('success', 'Mailbox creata con successo!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mailbox = MailBox::find($id); // Trova la mailbox per ID
        $customers = Customer::all(); // Recupera tutti i clienti disponibili

        // Controlla se la mailbox esiste
        if (!$mailbox) {
            return redirect()->back()->with('error', 'MailBox non trovata');
        }

        return view('mailboxes.show', compact('mailbox', 'customers'));
    }

    public function associateCustomerToMailBox(Request $request, $id)
    {
        $mailbox = MailBox::find($id); // Trova la mailbox

        // Controlla se la mailbox esiste
        if (!$mailbox) {
            return redirect()->back()->with('error', 'MailBox non trovata');
        }

        // Associa il cliente
        $mailbox->customer_id = $request->input('customer_id');
        $mailbox->save();

        return redirect()->route('mailboxes.index')->with('success', 'MailBox associata con successo!');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Recupera la mailbox esistente
        $mailbox = Mailbox::findOrFail($id);

        // Validazione dei dati
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'email' => 'required|email|unique:mailboxes,email,' . $mailbox->id, // Ignora l'email della mailbox corrente
            'server' => 'required|string|max:255',
            'IMAPport' => 'required|integer|min:1',
            'SMTPport' => 'required|integer|min:1',
        ]);

        // Aggiornamento della mailbox
        $mailbox->update($validatedData);

        // Reindirizzamento alla lista delle mailbox con un messaggio di successo
        return redirect()->route('mailboxes.index')->with('success', 'Mailbox aggiornata con successo!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getMailboxes(Request $request)
    {
        $dataTableController = new DataTableController();

        // Definisci la query con le relazioni tra mailboxes e customers
        $query = Mailbox::query()
            ->leftJoin('customers', 'mailboxes.customer_id', '=', 'customers.id')
            ->select('mailboxes.*', 'customers.name'); // Alias corretto per la colonna customer_name

        // Chiama il metodo generico del DataTableController
        return $dataTableController->getDataTable($request, $query);
    }

}
