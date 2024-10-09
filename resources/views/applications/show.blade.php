@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dettaglio Applicazione: {{ $application->label }}</h1>

    <form action="{{ route('applications.associateCustomer', $application->id) }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="label">Nome Applicazione</label>
                <input type="text" name="label" class="form-control" value="{{ $application->label }}" disabled>
            </div>
            <div class="col-md-6">
                <label for="application">Tipo Applicazione</label>
                <input type="text" name="application" class="form-control" value="{{ $application->application }}" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="app_fqdn">FQDN</label>
                <input type="text" name="app_fqdn" class="form-control" value="{{ $application->app_fqdn }}" disabled>
            </div>
            <div class="col-md-6">
                <label for="sys_user">Sys User</label>
                <input type="text" name="sys_user" class="form-control" value="{{ $application->sys_user }}" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="mysql_db_name">Nome DB MySQL</label>
                <input type="text" name="mysql_db_name" class="form-control" value="{{ $application->mysql_db_name }}" disabled>
            </div>
            <div class="col-md-6">
                <label for="mysql_user">Utente MySQL</label>
                <input type="text" name="mysql_user" class="form-control" value="{{ $application->mysql_user }}" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="customer_id">Seleziona Cliente:</label>
                <select name="customer_id" class="form-control">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $application->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Associa Cliente</button>
    </form>
</div>
@endsection

