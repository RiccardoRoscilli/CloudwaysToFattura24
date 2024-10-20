@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="jumbotron text-center">
        <h1 class="display-4">Benvenuto su Hosting App!</h1>
        <p class="lead">Gestisci facilmente la configurazione dei tuoi server Cloudways, le API di Fattura24 e la configurazione delle tue email da un'unica piattaforma.</p>
    </div>

    <!-- Sezione di spiegazione -->
    <div class="row">
        <!-- Configurazione Cloudways -->
        <div class="col-md-4">
            <h3>Configurazione Cloudways</h3>
            <p>Inizia configurando l'accesso API di Cloudways. Inserisci la tua API Key e l'email associata per poter gestire i tuoi server e le applicazioni direttamente da questa piattaforma.</p>
            <a href="{{ route('configuration.edit') }}" class="btn btn-primary">Configura Cloudways</a>
        </div>

        <!-- Configurazione Email -->
        <div class="col-md-4">
            <h3>Configurazione Email</h3>
            <p>Configura i dettagli del tuo server di posta per inviare notifiche e messaggi ai tuoi clienti. Puoi impostare SMTP, indirizzi e-mail e molto altro.</p>
            <a href="{{ route('configuration.edit') }}" class="btn btn-primary">Configura Email</a>
        </div>

        <!-- Configurazione Fattura24 -->
        <div class="col-md-4">
            <h3>Configurazione Fattura24</h3>
            <p>Connetti la tua API Key di Fattura24 per sincronizzare e gestire i clienti, creare fatture e molto altro. Assicurati di avere una chiave valida.</p>
            <a href="{{ route('configuration.edit') }}" class="btn btn-primary">Configura Fattura24</a>
        </div>
    </div>

    <!-- Sezione FAQ / Suggerimenti -->
    <div class="mt-5">
        <h3>Domande frequenti</h3>
        <div class="accordion" id="faqAccordion">
            <!-- Domanda 1 -->
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Come configurare l'API di Cloudways?
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        Per configurare Cloudways, vai nella sezione "Configurazione" e inserisci la tua API Key e l'email associata. Poi, potrai testare la connessione cliccando sul pulsante "Test API".
                    </div>
                </div>
            </div>

            <!-- Domanda 2 -->
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Come configurare il server SMTP per l'invio di email?
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="card-body">
                        Vai nella sezione di configurazione e inserisci i dettagli del server SMTP (host, porta, username, password). Dopo aver salvato, puoi testare l'invio di email di prova.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pulsanti rapidi -->
    <div class="mt-5 text-center">
        <a href="{{ route('customers.index') }}" class="btn btn-info">Gestisci Clienti</a>
        <a href="{{ route('servers.index') }}" class="btn btn-info">Gestisci Server</a>
    </div>
</div>
@endsection

