@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>{{ isset($service) ? 'Modifica Servizio' : 'Crea Nuovo Servizio' }}</h1>

        <form action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}" method="POST">
            @csrf
            @if (isset($service))
                @method('PUT')
            @endif

            <div class="row">
                <!-- Nome -->
                <div class="col-md-6 mb-3">
                    <label for="service_name" class="form-label">Nome Servizio</label>
                    <input type="text" class="form-control @error('service_name') is-invalid @enderror" id="service_name" name="service_name"
                        value="{{ old('service_name', $service->service_name ?? '') }}" required>
                    @error('service_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prezzo -->
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Prezzo (€)</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01"
                        value="{{ old('price', $service->price ?? '') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Cliente -->
                <div class="col-md-6 mb-3">
                    <label for="customer_id" class="form-label">Cliente Associato</label>
                    <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                        <option value="" disabled selected>Seleziona un cliente</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ old('customer_id', $service->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipo Servizio -->
                <div class="col-md-6 mb-3">
                    <label for="service_type" class="form-label">Tipo di Servizio</label>
                    <select class="form-select @error('service_type') is-invalid @enderror" id="service_type" name="service_type" required>
                        <option value="hosting" {{ old('service_type', $service->service_type ?? '') === 'hosting' ? 'selected' : '' }}>
                            Hosting
                        </option>
                        <option value="dominio" {{ old('service_type', $service->service_type ?? '') === 'dominio' ? 'selected' : '' }}>
                            Dominio
                        </option>
                        <option value="sendgrid" {{ old('service_type', $service->service_type ?? '') === 'sendgrid' ? 'selected' : '' }}>
                            SendGrid
                        </option>
                    </select>
                    @error('service_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Scadenza -->
                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Data di Scadenza</label>
                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date"
                        value="{{ old('expiry_date', $service->expiry_date ?? '') }}" required>
                    @error('expiry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Frequenza di Fatturazione -->
                <div class="col-md-6 mb-3">
                    <label for="billing_frequency" class="form-label">Frequenza di Fatturazione</label>
                    <select class="form-select @error('billing_frequency') is-invalid @enderror" id="billing_frequency" name="billing_frequency" required>
                        <option value="annual" {{ old('billing_frequency', $service->billing_frequency ?? '') === 'annual' ? 'selected' : '' }}>
                            Annuale
                        </option>
                        <option value="quarterly" {{ old('billing_frequency', $service->billing_frequency ?? '') === 'quarterly' ? 'selected' : '' }}>
                            Trimestrale
                        </option>
                    </select>
                    @error('billing_frequency')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Servizio Attivo -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Servizio Attivo</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $service->is_active ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Attivo</label>
                    </div>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">{{ isset($service) ? 'Aggiorna' : 'Crea' }}</button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">Annulla</a>
                </div>
            </div>
        </form>
    </div>
@endsection
