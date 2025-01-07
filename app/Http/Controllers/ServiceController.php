<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(10);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        $customers = Customer::all(); // Recupera tutti i clienti
        
        return view('services.edit', compact( 'customers'));
    }
    

    public function store(Request $request)
    {
         
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_type' => 'required',
            'price' => 'required|numeric|min:0',
            'billing_frequency' => 'required|in:annual,quarterly,monthly',
            'expiry_date' => 'nullable|date',
            'is_active' => 'boolean',
            'customer_id' => 'required', // Assicurati che il cliente esista
        ]);
    
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        
        Service::create($validated);
    
        return redirect()->route('services.index')->with('success', 'Servizio creato con successo!');
    }
    

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $customers = Customer::all(); // Recupera tutti i clienti
        return view('services.edit', compact('service', 'customers'));
    }
    
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
    
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_type' => 'required',
            'price' => 'required|numeric|min:0',
            'billing_frequency' => 'required|in:annual,quarterly,monthly',
            'expiry_date' => 'nullable|date',
            'is_active' => 'boolean',
            'customer_id' => 'required', // Assicurati che il cliente esista
        ]);
    
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
    
        $service->update($validated);
    
        return redirect()->route('services.index')->with('success', 'Servizio aggiornato con successo!');
    }
    

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Servizio eliminato con successo.');
    }
    public function getServices(Request $request)
    {
        $dataTableController = new DataTableController();
        $query = Service::query()
        ->leftJoin('customers', 'services.customer_id', '=', 'customers.id')
            ->select('services.*', 'customers.name');
    
        // Usare DataTables per generare la risposta
        return $dataTableController->getDataTable($request, $query);
    }
}
