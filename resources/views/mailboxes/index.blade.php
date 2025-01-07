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
        <!-- Pulsante per creare una nuova mailbox -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Gestione Mailbox</h2>
            <a href="{{ route('mailboxes.create') }}" class="btn btn-success">Crea Nuova Mailbox</a>
        </div>

        <!-- Tabella delle mailbox -->
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
                        data: 'name',
                        name: 'customers.name',
                        render: function(data, type, row) {
                            return data ? data : 'Nessun cliente'; // Mostra 'Nessun cliente' se customer è null
                        }
                    },
                    {
                        data: 'id', // ID dell'applicazione
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <a href="/mailboxes/${data}/edit" class="btn btn-sm btn-info">Modifica</a>
                                <form action="/mailboxes/${data}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questa mailbox?')">Elimina</button>
                                </form>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@endsection
