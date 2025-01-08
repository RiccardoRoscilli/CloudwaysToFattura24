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
                    <th>Cliente</th>
                    <th>Totale</th>
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
            var entity = 'order';
            var url = "/datatable/" + entity;
            var status = 'complete'; // Puoi cambiarlo dinamicamente
            var url = `/datatable/order?status=${status}`;

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "GET", // Usa il metodo GET
                    data: {
                        status: 'complete' // Passa un valore statico o dinamico
                    }
                },
                columns: [{
                        data: 'name', // Assumendo che ci sia un campo customer_name o simile
                        name: 'name',
                        title: 'Cliente'
                    },
                    {
                        data: 'amount',
                        name: 'orders.amount',
                        title: 'Totale'
                    },
                    {
                        data: 'status',
                        name: 'orders.status',
                        title: 'Stato'
                    },
                    {
                        data: 'id', // Usa 'id' dell'ordine per i pulsanti di azione
                        name: 'orders.id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var disabledFattura24 = row.status === 'sentF24' ? 'disabled' : '';
                            return `
     
        <a href="/orders/${data}/edit" class="btn btn-sm btn-info">Modifica</a>
        <button class="btn btn-sm btn-success send-to-fattura24" 
                data-order-id="${data}" ${disabledFattura24}>
            Invia a Fattura24
        </button>
        <button class="btn btn-sm btn-warning send-mail" 
                data-order-id="${data}">
            Invia Mail
        </button>
    `;
                        }
                    }
                ]
            });

            // Gestione del pulsante per inviare a Fattura24
            $(document).on('click', '.send-to-fattura24', function() {
                var orderId = $(this).data('order-id');
                var button = $(this); // Riferimento al pulsante cliccato

                $.ajax({
                    url: '/orders/' + orderId + '/sendToFattura24',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Mostra un messaggio di successo
                        // alert(response.message);

                        // Ricarica la tabella per riflettere i cambiamenti
                        table.ajax.reload(null,
                            false); // False per non riposizionare la tabella

                        // Disabilita il pulsante specifico
                        button.prop('disabled', true);
                    },
                    error: function(xhr) {
                        alert('Errore: ' + (xhr.responseJSON.message ||
                            'Qualcosa è andato storto.'));
                    }
                });
            });
            // invio email 
            $(document).on('click', '.send-mail', function() {
                var orderId = $(this).data('order-id');
                var button = $(this); // Riferimento al pulsante cliccato

                $.ajax({
                    url: '/orders/' + orderId + '/sendMail',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        //   alert(response.message); // Mostra un messaggio di successo
                        table.ajax.reload(null,
                            false); // False per mantenere la posizione della tabella

                    },
                    error: function(xhr) {
                        alert('Errore: ' + (xhr.responseJSON.message ||
                            'Qualcosa è andato storto.'));
                    }
                });
            });
        });
    </script>
@endsection
