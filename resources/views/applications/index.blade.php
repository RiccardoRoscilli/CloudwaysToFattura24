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
        <table class="table table-bordered yajra-datatable" style="width: 100%;">
            <thead>
                <tr>
                    <th>Nome Applicazione</th>
                    <th>Versione</th>
                    <th>Server ID</th>
                    <th>Cliente Associato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(function() {
            var entity = 'application'; // Definisci il model dinamicamente
            var url = "/datatable/" + entity;
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                 stateSave: true,
                serverSide: true,
                ajax: url,
                columns: [{
                        data: 'label',
                        name: 'label'
                    },
                    {
                        data: 'app_version',
                        name: 'app_version'
                    },
                    {
                        data: 'server_id',
                        name: 'server_id'
                    },
                    {
                        data: 'name',
                        name: 'customers.name',
                        render: function(data, type, row) {
                            return data ? data :
                                'Nessun cliente'; // Mostra 'Nessun cliente' se customer è null
                        }
                    },
                    {
                        data: 'id', // ID dell'applicazione
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<a href="/applications/' + data +
                                '" class="btn btn-sm btn-primary">Associa Cliente</a>';
                        }
                    }

                ]
            });
        });
    </script>
@endsection
