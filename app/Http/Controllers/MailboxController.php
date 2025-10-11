<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mailbox;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class MailboxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        
        // Validazione
        $validatedData = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'mailbox_email' => ['required', 'email', 'unique:mailboxes,mailbox_email'],
            'server' => ['required', 'string', 'max:255'],
            'IMAPport' => ['required', 'integer', 'min:1'],
            'SMTPport' => ['required', 'integer', 'min:1'],
        ]);
         
        Mailbox::create($validatedData);

        return redirect()->route('mailboxes.index')->with('success', 'Mailbox creata con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mailbox = Mailbox::find($id);
        $customers = Customer::all();

        if (!$mailbox) {
            return redirect()->back()->with('error', 'Mailbox non trovata');
        }

        return view('mailboxes.show', compact('mailbox', 'customers'));
    }

    public function associateCustomerToMailBox(Request $request, $id)
    {
        $mailbox = Mailbox::find($id);

        if (!$mailbox) {
            return redirect()->back()->with('error', 'Mailbox non trovata');
        }

        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
        ]);

        $mailbox->customer_id = $request->input('customer_id');
        $mailbox->save();

        return redirect()->route('mailboxes.index')->with('success', 'Mailbox associata con successo!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mailbox = Mailbox::findOrFail($id);

        $validatedData = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'mailbox_email' => [
                'required',
                'email',
                Rule::unique('mailboxes', 'mailbox_email')->ignore($mailbox->id),
            ],
            'server' => ['required', 'string', 'max:255'],
            'IMAPport' => ['required', 'integer', 'min:1'],
            'SMTPport' => ['required', 'integer', 'min:1'],
        ]);

        $mailbox->update($validatedData);

        return redirect()->route('mailboxes.index')->with('success', 'Mailbox aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mailbox = Mailbox::findOrFail($id);
        $mailbox->delete();

        return redirect()->route('mailboxes.index')->with('success', 'Mailbox eliminata con successo!');
    } 


    public function getMailboxes(Request $request)
    {
        $dataTableController = new DataTableController();

        $query = Mailbox::query()
            ->leftJoin('customers', 'mailboxes.customer_id', '=', 'customers.id')
            ->select([
                'mailboxes.id',
                'mailboxes.mailbox_email',
                'mailboxes.server',
                'mailboxes.IMAPport',
                'mailboxes.SMTPport',
                \DB::raw('customers.name'),
            ]);

        return $dataTableController->getDataTable($request, $query);
    }


}
