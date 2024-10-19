@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dettagli Application: {{ $application->label }}</h1>

    <div class="row">
        <!-- Colonna Sinistra -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="cloudways_app_id" class="form-label">Cloudways App ID</label>
                <input type="text" class="form-control" id="cloudways_app_id" value="{{ $application->cloudways_app_id }}" readonly>
            </div>

            <div class="mb-3">
                <label for="label" class="form-label">Label</label>
                <input type="text" class="form-control" id="label" value="{{ $application->label }}" readonly>
            </div>

            <div class="mb-3">
                <label for="application" class="form-label">Application</label>
                <input type="text" class="form-control" id="application" value="{{ $application->application }}" readonly>
            </div>

            <div class="mb-3">
                <label for="app_version" class="form-label">App Version</label>
                <input type="text" class="form-control" id="app_version" value="{{ $application->app_version }}" readonly>
            </div>

            <div class="mb-3">
                <label for="app_fqdn" class="form-label">App FQDN</label>
                <input type="text" class="form-control" id="app_fqdn" value="{{ $application->app_fqdn }}" readonly>
            </div>

            <div class="mb-3">
                <label for="sys_user" class="form-label">Sys User</label>
                <input type="text" class="form-control" id="sys_user" value="{{ $application->sys_user }}" readonly>
            </div>

            <div class="mb-3">
                <label for="cname" class="form-label">CNAME</label>
                <input type="text" class="form-control" id="cname" value="{{ $application->cname }}" readonly>
            </div>

            <div class="mb-3">
                <label for="server_id" class="form-label">Server ID</label>
                <input type="text" class="form-control" id="server_id" value="{{ $application->server_id }}" readonly>
            </div>

            <div class="mb-3">
                <label for="created_at" class="form-label">Creato il</label>
                <input type="text" class="form-control" id="created_at" value="{{ $application->created_at }}" readonly>
            </div>

            <div class="mb-3">
                <label for="updated_at" class="form-label">Aggiornato il</label>
                <input type="text" class="form-control" id="updated_at" value="{{ $application->updated_at }}" readonly>
            </div>
        </div>

        <!-- Colonna Destra -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="app_user" class="form-label">App User</label>
                <input type="text" class="form-control" id="app_user" value="{{ $application->app_user }}" readonly>
            </div>

            <div class="mb-3">
                <label for="app_password" class="form-label">App Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="app_password" value="{{ $application->app_password }}" readonly>
                    <span class="input-group-text">
                        <i class="fa fa-eye" id="toggleAppPassword" style="cursor: pointer;"></i>
                    </span>
                    <span class="input-group-text">
                        <i class="fa fa-copy" id="copyAppPassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label for="sys_password" class="form-label">Sys Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="sys_password" value="{{ $application->sys_password }}" readonly>
                    <span class="input-group-text">
                        <i class="fa fa-eye" id="toggleSysPassword" style="cursor: pointer;"></i>
                    </span>
                    <span class="input-group-text">
                        <i class="fa fa-copy" id="copySysPassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label for="mysql_db_name" class="form-label">MySQL DB Name</label>
                <input type="text" class="form-control" id="mysql_db_name" value="{{ $application->mysql_db_name }}" readonly>
            </div>

            <div class="mb-3">
                <label for="mysql_user" class="form-label">MySQL User</label>
                <input type="text" class="form-control" id="mysql_user" value="{{ $application->mysql_user }}" readonly>
            </div>

            <div class="mb-3">
                <label for="mysql_password" class="form-label">MySQL Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="mysql_password" value="{{ $application->mysql_password }}" readonly>
                    <span class="input-group-text">
                        <i class="fa fa-eye" id="toggleMysqlPassword" style="cursor: pointer;"></i>
                    </span>
                    <span class="input-group-text">
                        <i class="fa fa-copy" id="copyMysqlPassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label for="webroot" class="form-label">Webroot</label>
                <input type="text" class="form-control" id="webroot" value="{{ $application->webroot }}" readonly>
            </div>

            <div class="mb-3">
                <label for="is_csr_available" class="form-label">CSR Disponibile</label>
                <input type="text" class="form-control" id="is_csr_available" value="{{ $application->is_csr_available ? 'Sì' : 'No' }}" readonly>
            </div>

            <div class="mb-3">
                <label for="lets_encrypt" class="form-label">Let's Encrypt</label>
                <input type="text" class="form-control" id="lets_encrypt" value="{{ $application->lets_encrypt }}" readonly>
            </div>

            <div class="mb-3">
                <label for="app_version_id" class="form-label">App Version ID</label>
                <input type="text" class="form-control" id="app_version_id" value="{{ $application->app_version_id }}" readonly>
            </div>

            <div class="mb-3">
                <label for="cms_app_id" class="form-label">CMS App ID</label>
                <input type="text" class="form-control" id="cms_app_id" value="{{ $application->cms_app_id }}" readonly>
            </div>

            <div class="mb-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" value="{{ $application->customer->name ?? 'Nessun Cliente Associato' }}" readonly>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('applications.index') }}" class="btn btn-secondary">Torna indietro</a>
    </div>
</div>
<script>
    function togglePasswordVisibility(inputId, toggleIconId) {
        const passwordField = document.getElementById(inputId);
        const icon = document.getElementById(toggleIconId);
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        icon.classList.toggle('fa-eye-slash'); // Cambia l'icona
    }

    function copyToClipboard(inputId) {
        const inputField = document.getElementById(inputId);
        navigator.clipboard.writeText(inputField.value);
        alert(inputField.id + ' copiata negli appunti');
    }

    document.getElementById('toggleAppPassword').addEventListener('click', function () {
        togglePasswordVisibility('app_password', 'toggleAppPassword');
    });

    document.getElementById('toggleSysPassword').addEventListener('click', function () {
        togglePasswordVisibility('sys_password', 'toggleSysPassword');
    });

    document.getElementById('toggleMysqlPassword').addEventListener('click', function () {
        togglePasswordVisibility('mysql_password', 'toggleMysqlPassword');
    });

    document.getElementById('copyAppPassword').addEventListener('click', function () {
        copyToClipboard('app_password');
    });

    document.getElementById('copySysPassword').addEventListener('click', function () {
        copyToClipboard('sys_password');
    });

    document.getElementById('copyMysqlPassword').addEventListener('click', function () {
        copyToClipboard('mysql_password');
    });
</script>

@endsection
