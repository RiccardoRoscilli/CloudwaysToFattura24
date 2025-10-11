@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($mailbox) ? 'Modifica Mailbox' : 'Crea Nuova Mailbox' }}</h1>

        {{-- Messaggi di errore globali --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <p class="mb-2"><strong>Correggi i seguenti errori:</strong></p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Messaggi di successo (se usi session flash altrove) --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ isset($mailbox) ? route('mailboxes.update', $mailbox->id) : route('mailboxes.store') }}"
            method="POST" novalidate>
            @csrf
            @if (isset($mailbox))
                @method('PUT')
            @endif

            <div class="row">
                <!-- Colonna Sinistra -->
                <div class="col-md-6">
                    {{-- Cliente --}}
                    <div class="form-group mb-3">
                        <label for="customer_id">Cliente</label>
                        <select name="customer_id" id="customer_id"
                            class="form-control @error('customer_id') is-invalid @enderror" required>
                            <option value="">Seleziona un cliente</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ (int) old('customer_id', $mailbox->customer_id ?? 139) === (int) $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email (rinominata) --}}
                    <div class="form-group mb-3">
                        <label for="mailbox_email">Email</label>
                        <input type="email" id="mailbox_email" name="mailbox_email"
                            class="form-control @error('mailbox_email') is-invalid @enderror"
                            value="{{ old('mailbox_email', $mailbox->mailbox_email ?? '') }}" required>
                        @error('mailbox_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Server --}}
                    <div class="form-group mb-3">
                        <label for="server">Server</label>
                        <input type="text" id="server" name="server"
                            class="form-control @error('server') is-invalid @enderror"
                            value="{{ old('server', $mailbox->server ?? 'secure.emailsrvr.com') }}" required>
                        @error('server')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Colonna Destra -->
                <div class="col-md-6">
                    {{-- IMAP --}}
                    <div class="form-group mb-3">
                        <label for="IMAPport">Porta IMAP</label>
                        <input type="number" id="IMAPport" name="IMAPport"
                            class="form-control @error('IMAPport') is-invalid @enderror"
                            value="{{ old('IMAPport', $mailbox->IMAPport ?? 993) }}" required>
                        @error('IMAPport')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SMTP --}}
                    <div class="form-group mb-3">
                        <label for="SMTPport">Porta SMTP</label>
                        <input type="number" id="SMTPport" name="SMTPport"
                            class="form-control @error('SMTPport') is-invalid @enderror"
                            value="{{ old('SMTPport', $mailbox->SMTPport ?? 465) }}" required>
                        @error('SMTPport')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                {{ isset($mailbox) ? 'Salva Modifiche' : 'Crea Mailbox' }}
            </button>
        </form>
    </div>
@endsection
