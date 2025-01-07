@extends('layouts.app')

@section('content')
     @if ($message = Session::get('success'))
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ $message }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Servizi</h2>
            <a href="{{ route('services.create') }}" class="btn btn-primary">Aggiungi Servizio</a>
        </div>
        <h1>Elenco Servizi</h1>
        <table class="table table-bordered yajra-datatable" style="width: 100%;">
            <thead>
                <tr>
                    <th>Nome Servizio</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Cliente</th>
                    
                    <th>Prezzo</th>
                    <th>Scadenza</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

   <script type="text/javascript">
    $(function() {
        var entity = 'service'; // Definisci il model dinamicamente
        var url = "/datatable/" + entity; // Costruisci l'URL dinamicamente
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            stateSave: true,
            serverSide: true,
            ajax: url,
            columns: [
                {
                    data: 'service_name',
                    name: 'service_name',
                    title: 'Nome Servizio'
                },
                  {
                    data: 'name',
                    name: 'name',
                    title: 'Cliente'
                },
                {
                    data: 'service_type',
                    name: 'service_type',
                    title: 'Tipo Servizio'
                },
                {
                    data: 'price',
                    name: 'price',
                    title: 'Prezzo'
                },
                {
                    data: 'billing_frequency',
                    name: 'billing_frequency',
                    title: 'Frequenza'
                },
                {
                    data: 'expiry_date',
                    name: 'expiry_date',
                    title: 'Data di Scadenza'
                },
                {
                    data: 'status',
                    name: 'status',
                    title: 'Stato'
                },
                {
                    data: 'id', // ID del servizio
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                           
                            <a href="/services/${data}/edit" class="btn btn-sm btn-info">Modifica</a>
                        `;
                    }
                }
            ]
        });
    });
</script>

@endsection
