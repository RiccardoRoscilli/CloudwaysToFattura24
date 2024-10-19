@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($customer) ? 'Modifica Cliente: ' . $customer->name : 'Crea Nuovo Cliente' }}</h1>

    <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
        @csrf
        @if(isset($customer))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Colonna Sinistra -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="name">Nome</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name ?? '') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="vat_number">Partita IVA</label>
                    <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $customer->vat_number ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="sdi_code">Codice SDI</label>
                    <input type="text" name="sdi_code" class="form-control" value="{{ old('sdi_code', $customer->sdi_code ?? '') }}">
                </div>
            </div>

            <!-- Colonna Destra -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="phone">Telefono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="company_name">Azienda</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $customer->company_name ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="fiscal_code">Codice Fiscale</label>
                    <input type="text" name="fiscal_code" class="form-control" value="{{ old('fiscal_code', $customer->fiscal_code ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="pec_email">PEC Email</label>
                    <input type="email" name="pec_email" class="form-control" value="{{ old('pec_email', $customer->pec_email ?? '') }}">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">{{ isset($customer) ? 'Salva Modifiche' : 'Crea Cliente' }}</button>
    </form>
</div>
@endsection
