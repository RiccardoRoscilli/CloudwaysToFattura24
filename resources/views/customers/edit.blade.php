@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($customer) ? 'Modifica Cliente: ' . $customer->name : 'Crea Nuovo Cliente' }}</h1>

    <form action="{{ isset($customer->id) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
        @csrf
        @if(isset($customer->id))
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
                    <label for="phone">Telefono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="mobile">Cellulare</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $customer->mobile ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="fax">Fax</label>
                    <input type="text" name="fax" class="form-control" value="{{ old('fax', $customer->fax ?? '') }}">
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
                    <label for="vat_number">Partita IVA</label>
                    <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $customer->vat_number ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="iban">IBAN</label>
                    <input type="text" name="iban" class="form-control" value="{{ old('iban', $customer->iban ?? '') }}">
                </div>
            </div>

            <!-- Colonna Destra -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="billing_street">Via</label>
                    <input type="text" name="billing_street" class="form-control" value="{{ old('billing_street', $customer->billing_street ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="billing_civic_number">Numero Civico</label>
                    <input type="text" name="billing_civic_number" class="form-control" value="{{ old('billing_civic_number', $customer->billing_civic_number ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="billing_city">Città</label>
                    <input type="text" name="billing_city" class="form-control" value="{{ old('billing_city', $customer->billing_city ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="billing_province">Provincia</label>
                    <input type="text" name="billing_province" class="form-control" value="{{ old('billing_province', $customer->billing_province ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="billing_postal_code">CAP</label>
                    <input type="text" name="billing_postal_code" class="form-control" value="{{ old('billing_postal_code', $customer->billing_postal_code ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="billing_country">Paese</label>
                    <input type="text" name="billing_country" class="form-control" value="{{ old('billing_country', $customer->billing_country ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="sdi_code">Codice SDI</label>
                    <input type="text" name="sdi_code" class="form-control" value="{{ old('sdi_code', $customer->sdi_code ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="pec_email">PEC Email</label>
                    <input type="email" name="pec_email" class="form-control" value="{{ old('pec_email', $customer->pec_email ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="customer_type">Tipo Cliente</label>
                    <input type="text" name="customer_type" class="form-control" value="{{ old('customer_type', $customer->customer_type ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="website">Sito Web</label>
                    <input type="text" name="website" class="form-control" value="{{ old('website', $customer->website ?? '') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="note">Note</label>
                    <textarea name="note" class="form-control">{{ old('note', $customer->note ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">{{ isset($customer) ? 'Salva Modifiche' : 'Crea Cliente' }}</button>
    </form>
</div>
@endsection
