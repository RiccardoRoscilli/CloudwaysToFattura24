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

    <div class="container mt-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Nuovo Cliente</a>
            <a href="{{ route('import.customers.form') }}" class="btn btn-info">Importa clienti da Fattura24</a>
        </div>

        <table class="table table-bordered yajra-datatable" style="width: 100%;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Partita IVA</th>
                    <th>SDI</th>
                    <th>Phone</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(function() {
            var entity = 'customer'; // Cambia questa variabile in base al model che desideri visualizzare

            var table = $('.yajra-datatable').DataTable({
                stateSave: true,
                stripeClasses: ['bg-light', 'bg-white'],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/it-IT.json',
                },
                processing: true,
                serverSide: true,
                ajax: "{{ url('/datatable') }}/" + entity, // URL dinamico per caricare i dati
                columns: [
                    { data: 'name', name: 'name', orderable: true },
                    { data: 'email', name: 'email', orderable: true },
                    { data: 'vat_number', name: 'vat_number', orderable: true },
                    { data: 'sdi_code', name: 'sdi_code', orderable: true },
                    { data: 'phone', name: 'phone', orderable: true },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false, // Impedisci la ricerca sulla colonna 'action'
                        render: function(data, type, row) {
                            return `
                                <a href="/customers/${data}/services" class="btn btn-info btn-sm">Servizi</a>
                                <a href="/customers/${data}/edit" class="btn btn-primary btn-sm">Modifica</a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@endsection
