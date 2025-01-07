<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        return view('servers.index');
    }
    public function edit(Server $server)
    {
        return view('servers.edit', compact('server'));
    }
    public function update(Request $request, Server $server)
{
    // Valida i dati
    $validated = $request->validate([
        'label' => 'required|string|max:255',
        'status' => 'required|string',
        'platform' => 'required|string',
        'cloud' => 'required|string',
        'public_ip' => 'required|ip',
        'volume_size' => 'required|integer',
        'master_user' => 'required|string',
        'master_password' => 'required|string',
    ]);

    // Aggiorna il server con i dati validati
    $server->update($validated);

    return redirect()->route('servers.index')->with('success', 'Server aggiornato con successo');
}

    // Metodo per restituire i dati in formato JSON per DataTables
    public function getServersData(Request $request)
    {
        $dataTableController = new DataTableController();

        $query = Server::query()
           // ->leftJoin('customers', 'servers.customer_id', '=', 'customers.id')
            ->select('servers.*');
        
        // Usa DataTables per generare la risposta
        return $dataTableController->getDataTable($request, $query);
    }
}
