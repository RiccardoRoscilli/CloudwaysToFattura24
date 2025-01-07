@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($mailbox) ? 'Modifica Mailbox' : 'Crea Nuova Mailbox' }}</h1>

    <form action="{{ isset($mailbox) ? route('mailboxes.update', $mailbox->id) : route('mailboxes.store') }}" method="POST">
        @csrf
        @if(isset($mailbox))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Colonna Sinistra -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="customer_id">Cliente</label>
                    <select name="customer_id" class="form-control" required>
                        <option value="">Seleziona un cliente</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" 
                                {{ old('customer_id', $mailbox->customer_id ?? 139) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ old('email', $mailbox->email ?? '') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="server">Server</label>
                    <input type="text" name="server" class="form-control" 
                           value="{{ old('server', $mailbox->server ?? 'secure.emailsrvr.com') }}" required>
                </div>
            </div>

            <!-- Colonna Destra -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="IMAPport">Porta IMAP</label>
                    <input type="number" name="IMAPport" class="form-control" 
                           value="{{ old('IMAPport', $mailbox->IMAPport ?? 993) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="SMTPport">Porta SMTP</label>
                    <input type="number" name="SMTPport" class="form-control" 
                           value="{{ old('SMTPport', $mailbox->SMTPport ?? 465) }}" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">{{ isset($mailbox) ? 'Salva Modifiche' : 'Crea Mailbox' }}</button>
    </form>
</div>
@endsection
