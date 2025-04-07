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
                    <th>Prezzo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal di conferma eliminazione -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Conferma Eliminazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sei sicuro di voler eliminare questa applicazione?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-danger">Elimina</button>
                    </form>
                </div>
            </div>
        </div>
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
                            return data ? data : 'Nessun cliente';
                        }
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                                <a href="/applications/${data}" class="btn btn-sm btn-primary">Associa Cliente</a>
                                <a href="/applications/${data}/edit" class="btn btn-sm btn-info">Visualizza Dettagli</a>
                                <button class="btn btn-sm btn-danger deleteButton" data-id="${data}">Elimina</button>
                            `;
                        }
                    }
                ]
            });

            // Evento per il pulsante elimina
            $(document).on('click', '.deleteButton', function() {
                var appId = $(this).data('id');
                var form = $('#deleteForm');
                form.attr('action', `/applications/${appId}`);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
