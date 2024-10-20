@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifica Configurazione </h1>
        <p>Ambiente corrente: {{ env('APP_ENV') }}</p>

        @if (env('APP_ENV') === 'demo')
        <p class="alert alert-warning">
            Attenzione: questa è una versione demo. Per motivi di sicurezza, ricordati di modificare le API e le password una volta terminato l'utilizzo della demo.
            <br>Inolre non lasciare i tuoi dati sulla configurazione, grazie.
        </p>
    @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('configuration.update') }}" method="POST">
            @csrf


            <!-- Sezione Configurazione Mail -->
            <h3>Configurazione Mail</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mail_mailer" class="form-label">Mail Mailer</label>
                        <input type="text" name="mail_mailer" id="mail_mailer" class="form-control"
                            value="{{ old('mail_mailer', $configuration->mail_mailer) }}" placeholder="es: smtp" required>
                    </div>

                    <div class="mb-3">
                        <label for="mail_host" class="form-label">Mail Host</label>
                        <input type="text" name="mail_host" id="mail_host" class="form-control"
                            value="{{ old('mail_host', $configuration->mail_host) }}" placeholder="es: smtp.mailtrap.io"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="mail_port" class="form-label">Mail Port</label>
                        <input type="text" name="mail_port" id="mail_port" class="form-control"
                            value="{{ old('mail_port', $configuration->mail_port) }}" placeholder="es: 465 o 587" required>
                    </div>

                    <div class="mb-3">
                        <label for="mail_username" class="form-label">Mail Username</label>
                        <input type="text" name="mail_username" id="mail_username" class="form-control"
                            value="{{ old('mail_username', $configuration->mail_username) }}"
                            placeholder="Inserisci lo username della mail">
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mail_from_address" class="form-label">Mail From Address</label>
                        <input type="email" name="mail_from_address" id="mail_from_address" class="form-control"
                            value="{{ old('mail_from_address', $configuration->mail_from_address) }}"
                            placeholder="es: hello@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="mail_from_name" class="form-label">Mail From Name</label>
                        <input type="text" name="mail_from_name" id="mail_from_name" class="form-control"
                            value="{{ old('mail_from_name', $configuration->mail_from_name) }}" placeholder="es: MyApp">
                    </div>
                    <div class="mb-3">
                        <label for="mail_encryption" class="form-label">Mail Encryption</label>
                        <input type="text" name="mail_encryption" id="mail_encryption" class="form-control"
                            value="{{ old('mail_encryption', $configuration->mail_encryption) }}"
                            placeholder="es: ssl o tls">
                    </div>
                    <div class="mb-3">
                        <label for="mail_password" class="form-label">Mail Password</label>
                        <input type="password" name="mail_password" id="mail_password" class="form-control"
                            value="{{ old('mail_password', $configuration->mail_password) }}"
                            placeholder="Inserisci la password della mail">
                    </div>


                </div>
            </div>

            <!-- Sezione Configurazione Cloudways -->
            <h3 class="mt-4">Configurazione Cloudways</h3>
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label for="cloudways_api_key" class="form-label">Cloudways API Key</label>
                        <input type="text" name="cloudways_api_key" id="cloudways_api_key" class="form-control"
                            value="{{ old('cloudways_api_key', $configuration->cloudways_api_key ?? '') }}"
                            placeholder="Inserisci la Cloudways API Key">
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="mb-3">
                        <label for="cloudways_email" class="form-label">Cloudways Email</label>
                        <input type="email" name="cloudways_email" id="cloudways_email" class="form-control"
                            value="{{ old('cloudways_email', $configuration->cloudways_email ?? '') }}"
                            placeholder="Inserisci l'email associata a Cloudways">
                    </div>
                </div>

                <!-- Pulsante per testare l'API di Cloudways -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="test-cloudways-api" class="btn btn-success w-100 mb-3">
                        Test API Cloudways
                        <span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                            style="display: none;"></span>
                    </button>
                </div>

                <!-- Messaggio di risultato -->
                <div id="cloudways-api-result" class="mt-3"></div>

            </div>


            <!-- Sezione Configurazione Fattura24 -->
            <h3 class="mt-4">Configurazione Fattura24</h3>
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label for="fattura24_api_key" class="form-label">Fattura24 API Key</label>
                        <input type="text" name="fattura24_api_key" id="fattura24_api_key" class="form-control"
                            value="{{ old('fattura24_api_key', $configuration->fattura24_api_key) }}"
                            placeholder="Inserisci la Fattura24 API Key">
                    </div>
                </div>


                <div class="col-md-2 d-flex align-items-end">
                    <!-- Pulsante per testare l'API di Fattura24 -->
                    <button type="button" id="test-fattura24-api" class="btn btn-success w-100 mb-3">Test API
                        Fattura24</button>
                </div>
                <div class="col-md-5">
                </div>
                <div id="fattura24-api-result" class="mt-3 d-none"></div>

            </div>


            <button type="submit" class="btn btn-primary mt-4">Salva Configurazione</button>
        </form>
    </div>

    <!-- Script per gestire le chiamate API -->
    <script>
        document.getElementById('test-cloudways-api').addEventListener('click', function() {
            var button = this;
            var spinner = document.getElementById('spinner');
            var resultDiv = document.getElementById('cloudways-api-result');

            // Cambia il testo del pulsante e mostra lo spinner
            button.disabled = true;
            button.classList.remove('btn-success', 'btn-danger');
            button.classList.add('btn-secondary');
            button.innerHTML =
                'Testing... <span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            fetch("{{ route('test.cloudways.api') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cloudways_api_key: document.getElementById('cloudways_api_key').value,
                        cloudways_email: document.getElementById('cloudways_email').value,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    // Se la risposta è un successo
                    if (data.message.includes('Autenticazione riuscita')) {
                        button.innerHTML = 'Success';
                        button.classList.remove('btn-secondary', 'btn-danger');
                        button.classList.add('btn-success');

                        // Mostra il messaggio di successo in verde
                        resultDiv.classList.remove('alert-danger');
                        resultDiv.classList.add('alert-success');
                        resultDiv.style.display = 'block';
                        resultDiv.textContent = data.message;
                    } else {
                        // Se la richiesta è fallita
                        button.innerHTML = 'Failed';
                        button.classList.remove('btn-secondary', 'btn-success');
                        button.classList.add('btn-danger');

                        // Mostra il messaggio di errore in rosso pastello
                        resultDiv.classList.remove('alert-success');
                        resultDiv.classList.add('alert-danger');
                        resultDiv.style.display = 'block';
                        resultDiv.textContent = 'Errore: ' + data.message;
                    }
                })
                .catch(error => {
                    // Se c'è un errore durante la richiesta
                    button.innerHTML = 'Failed';
                    button.classList.remove('btn-secondary', 'btn-success');
                    button.classList.add('btn-danger');

                    // Mostra l'errore generico in rosso pastello
                    resultDiv.classList.remove('alert-success');
                    resultDiv.classList.add('alert-danger');
                    resultDiv.style.display = 'block';
                    resultDiv.textContent = 'Errore nella connessione all\'API';
                })
                .finally(() => {
                    // Rimuovi lo spinner e abilita il pulsante
                    spinner.style.display = 'none';
                    button.disabled = false;
                });
        });


        document.getElementById('test-fattura24-api').addEventListener('click', function() {
            // Resetta lo stile precedente e rimuovi d-none
            const resultDiv = document.getElementById('fattura24-api-result');
            resultDiv.textContent = '';
            resultDiv.classList.remove('alert-success', 'alert-danger',
            'd-none'); // Rimuove tutte le classi precedenti

            // Mostra lo spinner durante la richiesta
            const button = document.getElementById('test-fattura24-api');
            button.innerHTML =
                'Test in corso... <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
            button.disabled = true;

            // Chiamata AJAX per testare l'API di Fattura24
            fetch("{{ route('test.fattura24.api') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        fattura24_api_key: document.getElementById('fattura24_api_key').value,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    // Rimuove lo spinner e abilita nuovamente il pulsante
                    button.innerHTML = 'Test API Fattura24';
                    button.disabled = false;

                    // Mostra il messaggio restituito dall'API
                    resultDiv.textContent = data.message;

                    // Cambia lo sfondo in base al successo o fallimento
                    if (data.message.includes('riuscito')) {
                        resultDiv.classList.add('alert', 'alert-success'); // Verde se successo
                    } else {
                        resultDiv.classList.add('alert', 'alert-danger'); // Rosso se fallimento
                    }
                })
                .catch(error => {
                    // Rimuove lo spinner e abilita nuovamente il pulsante in caso di errore
                    button.innerHTML = 'Test API Fattura24';
                    button.disabled = false;

                    // Mostra un messaggio di errore e cambia lo sfondo in rosso
                    resultDiv.textContent = 'Errore nella connessione all\'API';
                    resultDiv.classList.add('alert', 'alert-danger');
                });
        });
    </script>
@endsection
