@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista dei Server</h1>

    <!-- Pulsante per importare i dati da Cloudways -->
    <div class="mb-3">
        <button id="import-btn" class="btn btn-primary">
            Importa dati da Cloudways
        </button>
    </div>

    <!-- Tabella per visualizzare i server -->
    <table class="table table-bordered" id="servers-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Label</th>
                <th>Status</th>
                <th>Platform</th>
                <th>Cloud</th>
                <th>Public IP</th>
               
            </tr>
        </thead>
    </table>
</div>

<!-- Script per DataTables -->
<script>
$(function() {
    $('#servers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('servers.data') !!}',  // Assicurati che la route per recuperare i dati sia corretta
        columns: [
            { data: 'id', name: 'id' },
            { data: 'label', name: 'label' },
            { data: 'status', name: 'status' },
            { data: 'platform', name: 'platform' },
            { data: 'cloud', name: 'cloud' },
            { data: 'public_ip', name: 'public_ip' },
          
        ]
    });
});
</script>

<!-- Script per la chiamata AJAX al pulsante di importazione -->
<script>
    $(document).ready(function() {
        $('#import-btn').click(function() {
            let button = $(this);
            button.text('Synching...').attr('disabled', true);

            $.ajax({
                url: "{{ route('cloudways.import') }}", // URL della route per l'importazione
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Importazione completata con successo
                    button.text('Import Done');
                    // Ricarica la tabella per visualizzare i nuovi dati
                    $('#servers-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    // Gestione degli errori
                    button.text('Import Failed');
                    console.log(xhr.responseText);
                },
                complete: function() {
                    // Abilitare nuovamente il pulsante
                    button.attr('disabled', false);
                }
            });
        });
    });
</script>
@endsection
