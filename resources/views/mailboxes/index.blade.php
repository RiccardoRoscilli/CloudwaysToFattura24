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
                    <th>Email</th>
                    <th>Server</th>
                    <th>IMAP Port</th>
                    <th>SMTP Port</th>
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
            var entity = 'mailbox'; // Nome corretto del model
            var url = "/datatable/" + entity;
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                ajax: {
                    url: url,
                    error: function(xhr, status, error) {
                        if (xhr.status === 401) {
                            // Se l'errore è Unauthenticated (401), reindirizza alla pagina di login
                            window.location.href = '/login';
                        }
                    }
                },
                columns: [{
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'server',
                        name: 'server'
                    },
                    {
                        data: 'IMAPport',
                        name: 'IMAPport'
                    },
                    {
                        data: 'SMTPport',
                        name: 'SMTPport'
                    },
                    {
                        data: 'customer_name',
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
                            return '<a href="/mailboxes/' + data +
                                '" class="btn btn-sm btn-primary">Associa Cliente</a>';
                        }
                    }

                ]
            });
        });
    </script>
@endsection
