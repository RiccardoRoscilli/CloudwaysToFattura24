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

    <!-- Modal Associa Cliente -->
    <div class="modal fade" id="associateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="associateForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Associa Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Mailbox: <strong id="associateEmail"></strong></p>
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="customer_id" id="associateCustomer" class="form-control" required>
                                <option value="">Seleziona un cliente</option>
                                @foreach(App\Models\Customer::orderBy('name')->get() as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">Associa</button>
                    </div>
                </form>
            </div>
        </div>
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
                        console.error('DataTables AJAX error:', status, error, xhr.responseText);
                        if (xhr.status === 401) {
                            window.location.href = '/login';
                        }
                    }
                },
                columns: [
  { data: 'mailbox_email', name: 'mailboxes.mailbox_email' },
  { data: 'server',        name: 'mailboxes.server' },
  { data: 'IMAPport',      name: 'mailboxes.IMAPport' },
  { data: 'SMTPport',      name: 'mailboxes.SMTPport' },
  {
    data: 'name',
    name: 'customers.name',
    render: function(data){ return data || 'Nessun cliente'; }
  },
  {
    data: null,
    name: 'mailboxes.id',
    orderable: false,
    searchable: false,
    render: function (data, type, row) {
      var html = `<a href="/mailboxes/${row.id}/edit" class="btn btn-sm btn-info">Modifica</a> `;
      if (!row.name) {
        html += `<button class="btn btn-sm btn-warning btn-associate" data-id="${row.id}" data-email="${row.mailbox_email}">Associa</button> `;
      }
      html += `<form action="/mailboxes/${row.id}" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger"
            onclick="return confirm('Sei sicuro di voler eliminare questa mailbox?')">Elimina</button>
        </form>`;
      return html;
    }
  }
],
order: [[0, 'asc']]

            });

            // Handler pulsante Associa
            $(document).on('click', '.btn-associate', function() {
                var id = $(this).data('id');
                var email = $(this).data('email');
                $('#associateEmail').text(email);
                $('#associateForm').attr('action', '/mailboxes/' + id + '/associate');
                $('#associateCustomer').val('');
                $('#associateModal').modal('show');
            });
        });
    </script>
@endsection
