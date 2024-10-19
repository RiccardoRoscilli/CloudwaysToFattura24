@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dettagli MailBox: {{ $mailbox->email }}</h1>

    <form action="{{ route('mailboxes.associate', $mailbox->id) }}" method="POST">
        @csrf
        <div class="row">
            <!-- Colonna Sinistra -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" value="{{ $mailbox->email }}" disabled>
                </div>

                <div class="form-group mb-3">
                    <label for="server">Server</label>
                    <input type="text" name="server" class="form-control" value="{{ $mailbox->server }}" disabled>
                </div>


            </div>

            <!-- Colonna Destra -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="SMTPport">SMTP Port</label>
                    <input type="text" name="SMTPport" class="form-control" value="{{ $mailbox->SMTPport }}" disabled>
                </div>
                <div class="form-group mb-3">
                    <label for="IMAPport">IMAP Port</label>
                    <input type="text" name="IMAPport" class="form-control" value="{{ $mailbox->IMAPport }}" disabled>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="customer_id">Associa Cliente</label>
                    <select name="customer_id" class="form-control" required>
                        <option value="">Seleziona un Cliente</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $mailbox->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Salva Associazione</button>
    </form>
</div>
@endsection
