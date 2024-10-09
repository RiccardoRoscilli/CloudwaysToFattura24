<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CloudwaysToFattura24</title>
</head>
<body>
    <h1>CloudwaysToFattura24</h1>

    <h2>Descrizione</h2>
    <p>CloudwaysToFattura24 è un'applicazione sviluppata in Laravel che permette di gestire e sincronizzare i servizi di hosting tra Cloudways e Fattura24, uno strumento di fatturazione italiano. L'app consente di importare dati sui server e le applicazioni direttamente da Cloudways tramite API e di generare automaticamente ordini di fatturazione su Fattura24 sempre tramite API, rendendo più efficiente la gestione delle fatture per i servizi rivenduti ai clienti.</p>

    <h2>Funzionalità principali</h2>
    <ul>
        <li><strong>Sincronizzazione dati da Cloudways:</strong> Importa i dettagli dei server e delle applicazioni in modo automatico tramie API, visualizzando i dati in modo ordinato con DataTables.
        Non è possibile importare le mailbox di Rackspace in quanto Cloudways è sprovvista di un API specifica.
        E' necessario quindi procedere all'import tramite file csv.</li>
                <li><strong>Sincronizzazione clienti da Fattura24:</strong> L'importazione dei clienti avviene tramite exporte del csv da Fattura24 ed import apposito form di upload sull'app. Purtroppo non 
                è possibile importare la rubrica tramite API perchè Fattura24 è sprovvista di una API specifica.</li>
        <li><strong>Associazione clienti:</strong> Associa ogni applicazione a un cliente specifico, in modo da avere sempre sotto controllo l'assegnazione dei servizi.</li>
        <li><strong>Creazione automatica di ordini su Fattura24:</strong> Utilizzando le API di Fattura24, genera automaticamente ordini su Fattura24, risparmiando tempo e minimizzando errori manuali.</li>
        <li><strong>Gestione dei pagamenti:</strong> Tiene traccia dello stato del pagamento per ogni ordine, inclusi dettagli come tipo di pagamento, data e ID. Aggiorna di conseguenza anche lo status dell'ordine.</li>
    </ul>

    <h2>Installazione</h2>
    <ol>
        <li>Clona il repository:
            <pre><code>git clone https://github.com/RiccardoRoscilli/CloudwaysToFattura24.git</code></pre>
        </li>
        <li>Configura il file <code>.env</code> per connettere l'applicazione a Cloudways e Fattura24, specificando le rispettive chiavi API e altri dettagli necessari.</li>
        <li>CLOUDWAYS_API_KEY, CLOUDWAYS_EMAIL, FATTURA24_API_KEY</li>
        <li>Installa le dipendenze:
            <pre><code>composer install</code></pre>
            <pre><code>npm install && npm run dev</code></pre>
        </li>
        <li>Esegui le migrazioni per configurare il database:
            <pre><code>php artisan migrate</code></pre>
        </li>
    </ol>

    <h2>Utilizzo</h2>
    <ul>
        <li><strong>Importazione dei dati:</strong> Tramite il pannello amministrativo, è possibile importare i dati da Cloudways per sincronizzare i server e le applicazioni.</li>
        <li><strong>Creazione di ordini:</strong> Gli ordini vengono creati automaticamente all'inizio di ogni trimestre e sono associati ai rispettivi clienti.</li>
        <li><strong>Invio a Fattura24:</strong> Con un semplice clic, è possibile inviare l'ordine a Fattura24 e generare automaticamente il documento di fatturazione.</li>
    </ul>

    <h2>Contributi</h2>
    <p>Se desideri contribuire al progetto, sentiti libero di creare pull request o di aprire nuove issue per segnalare bug o suggerire miglioramenti. Oppure scrivi a riccardo[punto]roscilli[at]gmail.com</p>

    <h2>Licenza</h2>
    <p>Questo progetto è rilasciato sotto la licenza GNU GPL 2.0 Consulta il file <code>LICENSE</code> per maggiori dettagli.</p>
</body>
</html>
