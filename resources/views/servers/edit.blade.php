@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifica Server</h1>

    <form action="{{ route('servers.update', $server->id) }}" method="POST">
        @csrf
        @method('POST')

        <div class="row">
            <!-- Colonna sinistra -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="label" class="form-label">Label</label>
                    <input type="text" class="form-control" id="label" name="label" value="{{ old('label', $server->label) }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" class="form-control" id="status" name="status" value="{{ old('status', $server->status) }}" required>
                </div>

                <div class="mb-3">
                    <label for="tenant_id" class="form-label">Tenant ID</label>
                    <input type="text" class="form-control" id="tenant_id" name="tenant_id" value="{{ old('tenant_id', $server->tenant_id) }}" required>
                </div>

                <div class="mb-3">
                    <label for="backup_frequency" class="form-label">Backup Frequency</label>
                    <input type="text" class="form-control" id="backup_frequency" name="backup_frequency" value="{{ old('backup_frequency', $server->backup_frequency) }}" required>
                </div>

                <div class="mb-3">
                    <label for="backup_retention" class="form-label">Backup Retention</label>
                    <input type="text" class="form-control" id="backup_retention" name="backup_retention" value="{{ old('backup_retention', $server->backup_retention) }}" required>
                </div>

                <div class="mb-3">
                    <label for="local_backups" class="form-label">Local Backups</label>
                    <input type="checkbox" class="form-check-input" id="local_backups" name="local_backups" {{ $server->local_backups ? 'checked' : '' }}>
                </div>

                <div class="mb-3">
                    <label for="backup_time" class="form-label">Backup Time</label>
                    <input type="text" class="form-control" id="backup_time" name="backup_time" value="{{ old('backup_time', $server->backup_time) }}" required>
                </div>

                <div class="mb-3">
                    <label for="is_terminated" class="form-label">Is Terminated</label>
                    <input type="checkbox" class="form-check-input" id="is_terminated" name="is_terminated" {{ $server->is_terminated ? 'checked' : '' }}>
                </div>
            </div>

            <!-- Colonna destra -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="platform" class="form-label">Platform</label>
                    <input type="text" class="form-control" id="platform" name="platform" value="{{ old('platform', $server->platform) }}" required>
                </div>

                <div class="mb-3">
                    <label for="cloud" class="form-label">Cloud</label>
                    <input type="text" class="form-control" id="cloud" name="cloud" value="{{ old('cloud', $server->cloud) }}" required>
                </div>

                <div class="mb-3">
                    <label for="region" class="form-label">Region</label>
                    <input type="text" class="form-control" id="region" name="region" value="{{ old('region', $server->region) }}" required>
                </div>

                <div class="mb-3">
                    <label for="instance_type" class="form-label">Instance Type</label>
                    <input type="text" class="form-control" id="instance_type" name="instance_type" value="{{ old('instance_type', $server->instance_type) }}" required>
                </div>

                <div class="mb-3">
                    <label for="server_fqdn" class="form-label">Server FQDN</label>
                    <input type="text" class="form-control" id="server_fqdn" name="server_fqdn" value="{{ old('server_fqdn', $server->server_fqdn) }}" required>
                </div>

                <div class="mb-3">
                    <label for="public_ip" class="form-label">Public IP</label>
                    <input type="text" class="form-control" id="public_ip" name="public_ip" value="{{ old('public_ip', $server->public_ip) }}" required>
                </div>

                <div class="mb-3">
                    <label for="volume_size" class="form-label">Volume Size</label>
                    <input type="text" class="form-control" id="volume_size" name="volume_size" value="{{ old('volume_size', $server->volume_size) }}" required>
                </div>

                <div class="mb-3">
                    <label for="master_user" class="form-label">Master User</label>
                    <input type="text" class="form-control" id="master_user" name="master_user" value="{{ old('master_user', $server->master_user) }}" required>
                </div>

                <div class="mb-3">
                    <label for="master_password" class="form-label">Master Password</label>
                    <input type="password" class="form-control" id="master_password" name="master_password" value="{{ old('master_password', $server->master_password) }}" required>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Salva</button>
            <a href="{{ route('servers.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>
@endsection
