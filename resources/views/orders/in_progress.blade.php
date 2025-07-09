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
    <!-- Modal di conferma per completare ordine -->
    <div class="modal fade" id="confirmCompleteModal" tabindex="-1" aria-labelledby="confirmCompleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCompleteModalLabel">Conferma Completamento Ordine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body">
                    Sei sicuro di voler segnare questo ordine come <strong>completato</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" id="confirmCompleteBtn">Conferma</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            var entity = 'order';
            var url = "/datatable/" + entity;
            var status = 'not_complete'; // Puoi cambiarlo dinamicamente
            var url = `/datatable/order?status=${status}`;

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                ajax: url,
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
           <button class="btn btn-sm btn-secondary mark-complete" 
            data-order-id="${data}" 
            data-bs-toggle="modal" data-bs-target="#confirmCompleteModal">
        Complete
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
            // Variabile globale per tenere traccia dell'ordine corrente
            let orderIdToComplete = null;

            $(document).on('click', '.mark-complete', function() {
                orderIdToComplete = $(this).data('order-id');
            });

            $('#confirmCompleteBtn').on('click', function() {
                if (!orderIdToComplete) return;

                $.ajax({
                    url: `/orders/${orderIdToComplete}/markComplete`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#confirmCompleteModal').modal('hide');
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        alert('Errore nel completare l\'ordine.');
                    }
                });
            });

        });
    </script>
@endsection
