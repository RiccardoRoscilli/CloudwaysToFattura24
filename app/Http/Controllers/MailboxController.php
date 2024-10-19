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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'customer_id' => 'required|exists:customers,id',
            // Non serve più richiedere server, IMAPport e SMTPport
        ]);

        // I valori per server, IMAPport, SMTPport vengono inseriti automaticamente con il default se non sono presenti
        MailBox::create([
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'customer_id' => $validatedData['customer_id'],
            'server' => $request->input('server', 'secure.emailsrvr.com'), // default dal DB, sovrascrivibile
            'IMAPport' => $request->input('IMAPport', 993), // default dal DB, sovrascrivibile
            'SMTPport' => $request->input('SMTPport', 465), // default dal DB, sovrascrivibile
        ]);

        return redirect()->route('mail-boxes.index')->with('success', 'MailBox creata con successo');
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MailBox $mailBox)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);

        // I valori per server, IMAPport, SMTPport vengono aggiornati o mantengono il default
        $mailBox->update([
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'customer_id' => $validatedData['customer_id'],
            'server' => $request->input('server', $mailBox->server), // Mantiene il valore esistente o sovrascrive
            'IMAPport' => $request->input('IMAPport', $mailBox->IMAPport),
            'SMTPport' => $request->input('SMTPport', $mailBox->SMTPport),
        ]);

        return redirect()->route('mail-boxes.index')->with('success', 'MailBox aggiornata con successo');
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
        $query = MailBox::with('customer') // Carica i dati del cliente associato
            ->leftJoin('customers', 'mailboxes.customer_id', '=', 'customers.id') // Effettua il join con la tabella customers
            ->select('mailboxes.*', 'customers.name as customer_name'); // Seleziona i campi necessari

        return datatables()->of($query)
            ->addColumn('customer_name', function ($mailbox) {
                return $mailbox->customer_name ? $mailbox->customer_name : 'Nessun cliente';
            })
            ->addColumn('action', function ($mailbox) {
                return '<a href="/mailboxes/' . $mailbox->id . '/edit" class="btn btn-sm btn-primary">Modifica</a>';
            })
            ->make(true);
    }

}
