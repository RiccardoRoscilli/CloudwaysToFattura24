@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($order) ? 'Modifica Ordine' : 'Crea Nuovo Ordine' }}</h1>

        <!-- Form per creare/modificare un ordine -->
        <form action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}" method="POST">
            @csrf
            @if(isset($order))
                @method('PUT')
            @endif

            <div class="row">
                <!-- Prima colonna -->
                <div class="col-md-6">
                    <!-- Selezione dell'applicazione -->
                    <div class="mb-3">
                        <label for="application_id" class="form-label">Applicazione</label>
                        <select class="form-control" name="application_id" id="application_id" required>
                            @foreach($applications as $application)
                                <option value="{{ $application->id }}" {{ isset($order) && $order->application_id == $application->id ? 'selected' : '' }}>
                                    {{ $application->label }} - {{ $application->application }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo per l'importo dell'ordine -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Importo</label>
                        <input type="number" class="form-control" name="amount" id="amount" value="{{ old('amount', isset($order) ? $order->amount : '') }}" required>
                    </div>

                    <!-- Data di pagamento -->
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Data di Pagamento</label>
                        <input type="date" class="form-control" name="payment_date" id="payment_date" value="{{ old('payment_date', isset($order) ? $order->payment_date->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <!-- Seconda colonna -->
                <div class="col-md-6">
                    <!-- Data di Inizio e Data di Fine sulla stessa riga -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Data di Inizio</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ old('start_date', isset($order) ? $order->start_date->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Data di Fine</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date', isset($order) ? $order->end_date->format('Y-m-d') : '') }}" required>
                        </div>
                    </div>

                    <!-- Tipo di pagamento -->
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">Tipo di Pagamento</label>
                        <input type="text" class="form-control" name="payment_type" id="payment_type" value="{{ old('payment_type', isset($order) ? $order->payment_type : '') }}" required>
                    </div>

                    <!-- ID pagamento -->
                    <div class="mb-3">
                        <label for="payment_id" class="form-label">ID Pagamento</label>
                        <input type="text" class="form-control" name="payment_id" id="payment_id" value="{{ old('payment_id', isset($order) ? $order->payment_id : '') }}">
                    </div>

                    <!-- Stato -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Stato</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="pending" {{ isset($order) && $order->status == 'pending' ? 'selected' : '' }}>In Attesa</option>
                            <option value="paid" {{ isset($order) && $order->status == 'paid' ? 'selected' : '' }}>Pagato</option>
                            <option value="cancelled" {{ isset($order) && $order->status == 'cancelled' ? 'selected' : '' }}>Annullato</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pulsante di invio -->
            <button type="submit" class="btn btn-primary">{{ isset($order) ? 'Salva Modifiche' : 'Crea Ordine' }}</button>
        </form>
    </div>
@endsection
