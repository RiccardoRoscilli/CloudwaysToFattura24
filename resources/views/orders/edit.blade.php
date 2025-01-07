@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dettagli Ordine</h1>

        <!-- Dati generali dell'ordine -->
        <div class="mb-4">
            <h3 class="mb-3">Informazioni Generali</h3>
            <table class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Informazione</th>
                        <th>Dettaglio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Cliente</th>
                        <td>{{ $order->customer->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Email Cliente</th>
                        <td>{{ $order->customer->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Stato</th>
                        <td>
                            <div class="form-group">
                                <select id="order-status" class="form-select form-select-sm w-auto">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="sent" {{ $order->status === 'sent' ? 'selected' : '' }}>Inviato
                                    </option>
                                    <option value="complete" {{ $order->status === 'complete' ? 'selected' : '' }}>
                                        Completato</option>
                                </select>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Totale</th>
                        <td id="order-total">€ {{ number_format($order->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <!-- Elenco delle applicazioni, servizi e mailbox associati -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Prezzo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr id="item-{{ $item->id }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ ucfirst($item->service_type) }}</td>
                        <td>
                            @if ($item->service_type === 'application')
                                {{ $item->application->label ?? 'N/A' }}
                            @elseif ($item->service_type === 'mailbox')
                                {{ $item->mailbox->email ?? 'N/A' }}
                            @else
                                {{ $item->service->service_name ?? 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if ($item->service_type === 'application')
                                Application
                            @elseif ($item->service_type === 'mailbox')
                                {{ $item->mailbox->email ?? 'N/A' }}
                            @else
                                {{ ucfirst($item->service->service_type) ?? 'N/A' }}
                            @endif
                        </td>
                        <td>
                            € <input type="number" id="price-input-{{ $item->id }}" value="{{ $item->price }}"
                                step="0.01" class="form-control form-control-sm w-auto d-inline">
                        </td>

                        <td>
                            <button class="btn btn-primary save-price" data-item-id="{{ $item->id }}">Salva
                                prezzo</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pulsanti Azione -->
        <div>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Torna alla Lista Ordini</a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.save-price').forEach((button) => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                const priceInput = document.querySelector(`#price-input-${itemId}`);

                if (!itemId || !priceInput) {
                    console.error('ID o input prezzo non trovati.');
                    return;
                }

                fetch(`/order-items/${itemId}/updatePrice`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            price: priceInput.value,
                            status: document.querySelector('#order-status')
                            .value, // Invia anche lo stato
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert('Prezzo aggiornato con successo!');
                        } else {
                            alert('Errore: ' + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Errore durante la richiesta:', error);
                        alert('Errore durante la richiesta.');
                    });
            });
        });
    </script>
@endsection
