@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dettaglio Cliente: {{ $customer->name }}</h1>

        <div>
            <h2>Applications</h2>
            @if($customer->applications->count() > 0)
            <table class="table table-bordered table-sm">
                <thead><tr><th>Nome</th><th>Tipo</th><th>FQDN</th><th>Prezzo</th><th>Attiva</th></tr></thead>
                <tbody>
                @foreach($customer->applications as $application)
                    <tr>
                        <td>{{ $application->label }}</td>
                        <td>{{ $application->application }}</td>
                        <td>{{ $application->app_fqdn }}</td>
                        <td>€{{ number_format($application->price ?? 0, 2) }}</td>
                        <td>{!! $application->is_active ? '<span class="badge bg-success">Sì</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p class="text-muted">Nessuna applicazione associata.</p>
            @endif
        </div>

        <!-- Form per associare una nuova Application -->
        <form action="{{ route('customers.addApplication', $customer->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="application">Seleziona Application:</label>
                <select name="application_id" id="application_id" class="form-control">
                    @foreach($applications as $application)
                        <option value="{{ $application->id }}">{{ $application->label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Associa Application</button>
        </form>

        <div class="mt-4">
            <h2>Servizi (Domini/Hosting)</h2>
            @if($services->count() > 0)
            <table class="table table-bordered table-sm">
                <thead><tr><th>Nome</th><th>Tipo</th><th>Prezzo</th><th>Scadenza</th><th>Frequenza</th><th>Attivo</th></tr></thead>
                <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->service_name }}</td>
                        <td>{{ $service->service_type }}</td>
                        <td>€{{ number_format($service->price, 2) }}</td>
                        <td>{{ $service->expiry_date }}</td>
                        <td>{{ $service->billing_frequency }}</td>
                        <td>{!! $service->is_active ? '<span class="badge bg-success">Sì</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p class="text-muted">Nessun servizio associato.</p>
            @endif
        </div>

        <div class="mt-4">
            <h2>Mailbox</h2>
            @if($mailboxes->count() > 0)
            <table class="table table-bordered table-sm">
                <thead><tr><th>Email</th><th>Server</th><th>IMAP</th><th>SMTP</th></tr></thead>
                <tbody>
                @foreach($mailboxes as $mb)
                    <tr>
                        <td>{{ $mb->mailbox_email }}</td>
                        <td>{{ $mb->server }}</td>
                        <td>{{ $mb->IMAPport }}</td>
                        <td>{{ $mb->SMTPport }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p class="text-muted">Nessuna mailbox associata.</p>
            @endif
        </div>
    </div>
@endsection
