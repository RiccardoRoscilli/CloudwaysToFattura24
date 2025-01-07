<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Customer;


class ApplicationController extends Controller
{
    public function index()
    {
        return view('applications.index');
    }

    public function getApplicationsData()
    {
        $applications = Application::all();

        return datatables()->of($applications)
            ->addColumn('action', function ($application) {
                return '<a href="/applications/' . $application->id . '/edit" class="btn btn-sm btn-primary">Modifica</a>';
            })
            ->make(true);
    }
    public function edit($id)
    {
        $application = Application::find($id); // Trova l'applicazione per ID
        $customers = Customer::all(); // Recupera tutti i clienti disponibili

        // Controlla se l'applicazione esiste
        if (!$application) {
            return redirect()->back()->with('error', 'Applicazione non trovata');
        }

        return view('applications.edit', compact('application', 'customers'));
    }

    public function update(Request $request, $id)
    {
      
        $application = Application::findOrFail($id);
         
        // Validazione del campo prezzo
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);
    
        // Aggiorna il prezzo
        $application->update($request->only(['price']));
    
        // Reindirizza alla vista index con un messaggio di successo
        return redirect()->route('applications.index')
                         ->with('success', 'Prezzo aggiornato con successo.');
    }
    

    public function associate($id)
    {

        $application = Application::find($id); // Trova l'applicazione per ID
        $customers = Customer::all(); // Recupera tutti i clienti disponibili

        // Controlla se l'applicazione esiste
        if (!$application) {
            return redirect()->back()->with('error', 'Applicazione non trovata');
        }

        return view('applications.show', compact('application', 'customers'));
    }

    public function associateCustomerToApplication(Request $request, $id)
    {
        $application = Application::find($id); // Trova l'applicazione

        // Controlla se l'applicazione esiste
        if (!$application) {
            return redirect()->back()->with('error', 'Applicazione non trovata');
        }

        // Associa il cliente
        $application->customer_id = $request->input('customer_id');
        $application->save();

        return redirect()->route('applications.index')->with('success', 'Application associata con successo!');
    }
    public function getApplications(Request $request)
    {
        $dataTableController = new DataTableController();

        // Definisci la query con le relazioni tra applications e customers
        $query = \App\Models\Application::query()
            ->leftJoin('customers', 'applications.customer_id', '=', 'customers.id')
            ->select('applications.*', 'customers.name');

        // Chiama il metodo generico del DataTableController
        return $dataTableController->getDataTable($request, $query);
    }
}
