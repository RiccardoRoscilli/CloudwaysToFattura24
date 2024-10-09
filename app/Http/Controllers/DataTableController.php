<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Customer;
use App\Models\Product; // Aggiungi altri modelli se necessario
use App\Models\Application;

class DataTableController extends Controller
{


    public function getDataTable(Request $request, $query)
    {
        // Recupera i parametri di DataTables
        $columns = $request->input('columns');
        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumn = $columns[$request->input('order.0.column')]['data'];
        $dir = $request->input('order.0.dir');

        // Conteggio totale dei record
        $totalData = $query->count();

        // Filtraggio, ordinamento e paginazione
        if (empty($request->input('search.value'))) {
            $data = $query->offset($start)
                ->limit($limit)
                ->orderBy($orderColumn, $dir)
                ->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $columns) {
                foreach ($columns as $column) {
                    if ($column['searchable'] === 'true') {
                        $query->orWhere($column['data'], 'LIKE', "%{$search}%");
                    }
                }
            });
          //  dd($query->toSql(), $query->getBindings());
            $data = $query->offset($start)
                ->limit($limit)
                ->orderBy($orderColumn, $dir)
                ->get();
            $totalFiltered = $query->count();
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data->toArray()
        ];

        return response()->json($json_data);
    }



    // Metodo per ottenere il modello in base all'entità passata
    protected function getModel($entity)
    {
        $models = [
            'customers' => Customer::class,
            'products' => Product::class,
            'applications' => Application::class,
            // Aggiungi altre entità se necessario
        ];

        return $models[$entity] ?? null;
    }
}
