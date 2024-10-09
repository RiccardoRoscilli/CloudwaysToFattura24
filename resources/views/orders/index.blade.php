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
            <a href="{{ route('orders.create') }}" class="btn btn-primary">Nuovo Ordine</a>
        </div>

        <table class="table table-bordered yajra-datatable" style="width: 100%;">
            <thead>
                <tr>

                    <th>Applicazione</th>
                    <th>Cliente</th>
                    <th>Importo</th>
                    <th>Status</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(function() {
            var entity = 'order'; // oppure 'application', 'customer', ecc.
            var url = "/datatable/" + entity;
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [{
                        data: 'label',
                        name: 'label'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <a href="/orders/${row.id}/edit" class="btn btn-primary btn-sm">Modifica</a>
                            <button class="btn btn-success btn-sm send-to-fattura24" data-order-id="${row.id}">Invia a F24</button>
                        `;
                        }
                    }
                ]
            });
            // Aggiungi l'handler per il click sul pulsante "Invia a F24"
            $(document).on('click', '.send-to-fattura24', function() {
                var orderId = $(this).data('order-id');

                $.ajax({
                    url: '/orders/' + orderId + '/sendToFattura24',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Errore: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endsection
