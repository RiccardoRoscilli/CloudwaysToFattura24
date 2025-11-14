CloudwaysToFattura24
====================

Descrizione
-----------

CloudwaysToFattura24 è un'applicazione sviluppata in Laravel che permette di gestire e sincronizzare i servizi di hosting tra Cloudways e Fattura24, uno strumento di fatturazione italiano. L'app consente di importare dati sui server e le applicazioni direttamente da Cloudways tramite API e di generare automaticamente ordini di fatturazione su Fattura24 sempre tramite API, rendendo più efficiente la gestione delle fatture per i servizi rivenduti ai clienti.

Funzionalità principali
-----------------------

*   **Sincronizzazione dati da Cloudways:** Importa i dettagli dei server e delle applicazioni in modo automatico tramite API, visualizzando i dati in modo ordinato con DataTables. Non è possibile importare le mailbox di Rackspace in quanto Cloudways è sprovvista di un'API specifica. È necessario quindi procedere all'import tramite file CSV.
*   **Sincronizzazione clienti da Fattura24:** L'importazione dei clienti avviene tramite export del CSV da Fattura24 ed import apposito form di upload sull'app. Purtroppo non è possibile importare la rubrica tramite API perché Fattura24 è sprovvista di un'API specifica.
*   **Associazione clienti:** Associa ogni applicazione a un cliente specifico, in modo da avere sempre sotto controllo l'assegnazione dei servizi.
*   **Creazione automatica di ordini su Fattura24:** Utilizzando le API di Fattura24, genera automaticamente ordini, risparmiando tempo e minimizzando errori manuali.
*   **Gestione dei pagamenti:** Tiene traccia dello stato del pagamento per ogni ordine, inclusi dettagli come tipo di pagamento, data e ID. Aggiorna di conseguenza anche lo status dell'ordine.


Installazione
-------------

1.  Clona il repository:
    
        git clone https://github.com/RiccardoRoscilli/CloudwaysToFattura24.git
    
2.  Configura il file `.env` per connettere l'applicazione a Cloudways e Fattura24, specificando le rispettive chiavi API e altri dettagli necessari (CLOUDWAYS\_API\_KEY, CLOUDWAYS\_EMAIL, FATTURA24\_API\_KEY).
    
3.  Installa le dipendenze:
    
        composer install
        npm install && npm run dev
    
4.  Esegui le migrazioni per configurare il database:
    
        php artisan migrate
    

Utilizzo
--------

*   **Importazione dei dati:** Tramite il pannello amministrativo, è possibile importare i dati da Cloudways per sincronizzare i server e le applicazioni.
*   **Creazione di ordini:** Gli ordini vengono creati automaticamente all'inizio di ogni trimestre e sono associati ai rispettivi clienti.
*   **Invio a Fattura24:** Con un semplice clic, è possibile inviare l'ordine a Fattura24 e generare automaticamente il documento di fatturazione.

Contributi
----------

Se desideri contribuire al progetto, sentiti libero di creare pull request o di aprire nuove issue per segnalare bug o suggerire miglioramenti. Oppure scrivi a riccardo\[punto\]roscilli\[at\]gmail.com

Licenza
-------

Questo progetto è rilasciato sotto la licenza GNU GPL 2.0. Consulta il file `LICENSE` per maggiori dettagli.
