@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifica Application</h1>

    <form action="{{ route('applications.update', $application->id) }}" method="POST">
        @csrf
        @method('POST')

        <div class="row">
            <!-- Colonna sinistra -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="label" class="form-label">Label</label>
                    <input type="text" class="form-control" id="label" name="label" value="{{ old('label', $application->label) }}" required>
                </div>

                <div class="mb-3">
                    <label for="application" class="form-label">Application</label>
                    <input type="text" class="form-control" id="application" name="application" value="{{ old('application', $application->application) }}" required>
                </div>

                <div class="mb-3">
                    <label for="app_version" class="form-label">App Version</label>
                    <input type="text" class="form-control" id="app_version" name="app_version" value="{{ old('app_version', $application->app_version) }}" required>
                </div>

                <div class="mb-3">
                    <label for="app_fqdn" class="form-label">App FQDN</label>
                    <input type="text" class="form-control" id="app_fqdn" name="app_fqdn" value="{{ old('app_fqdn', $application->app_fqdn) }}" required>
                </div>
            </div>

            <!-- Colonna destra -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sys_user" class="form-label">Sys User</label>
                    <input type="text" class="form-control" id="sys_user" name="sys_user" value="{{ old('sys_user', $application->sys_user) }}" required>
                </div>

                <div class="mb-3">
                    <label for="app_user" class="form-label">App User</label>
                    <input type="text" class="form-control" id="app_user" name="app_user" value="{{ old('app_user', $application->app_user) }}" required>
                </div>

                <div class="mb-3">
                    <label for="app_password" class="form-label">App Password</label>
                    <input type="password" class="form-control" id="app_password" name="app_password" value="{{ old('app_password', $application->app_password) }}" required>
                </div>

                <div class="mb-3">
                    <label for="sys_password" class="form-label">Sys Password</label>
                    <input type="password" class="form-control" id="sys_password" name="sys_password" value="{{ old('sys_password', $application->sys_password) }}" required>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Salva</button>
            <a href="{{ route('applications.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>
@endsection
