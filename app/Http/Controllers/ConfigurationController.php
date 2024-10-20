<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Services\CloudwaysService;
use App\Services\Fattura24Service;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    protected $fattura24Service;
    protected $cloudwaysService;
    // Dependency injection del servizio Cloudways e Fattura24
    public function __construct(Fattura24Service $fattura24Service, CloudwaysService $cloudwaysService)
    {
        $this->fattura24Service = $fattura24Service;
        $this->cloudwaysService = $cloudwaysService;
    }

    public function edit()
    {
        // Recupera l'utente autenticato
        $user = auth()->user();

        // Recupera la configurazione dell'utente
        $configuration = \App\Models\Configuration::where('user_id', $user->id)->first();

        // Passa la configurazione alla vista
        return view('configuration.edit', compact('configuration'));
    }


    public function update(Request $request)
    {
        // Validazione dei dati in arrivo
        $request->validate([
            'mail_mailer' => 'nullable|string|max:255',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string|max:255',
            'cloudways_api_key' => 'nullable|string|max:255',
            'cloudways_email' => 'nullable|email',
            'fattura24_api_key' => 'nullable|string|max:255',
            'test_variable' => 'nullable|string|max:255',
        ]);

        // Ottieni l'utente autenticato
        $user = auth()->user();



        // Usa updateOrCreate per aggiornare o creare una configurazione
        $configuration = Configuration::updateOrCreate(
            ['user_id' => $user->id], // Condizione per trovare la configurazione
            $request->only([
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name',
                'cloudways_api_key',
                'cloudways_email',
                'fattura24_api_key',
                'test_variable'
            ]) // Dati da aggiornare
        );

        return redirect()->route('configuration.edit')->with('success', 'Configurazione aggiornata con successo!');
    }



    // Metodo per testare l'API di Cloudways
    public function testCloudwaysApi(Request $request)
    {
        // Chiamata al servizio per autenticarsi con Cloudways
        $token =  $this->cloudwaysService->authenticate();


        Log::info('Risposta dall\'API Cloudways:' . $token); // Questo loggherà la risposta come array

        // Verifica se c'è un messaggio di errore nella risposta
        if (strpos($token, 'Client error') !== false) {
            return response()->json(['message' => 'Errore durante l\'autenticazione. Token: ' . $token]);
        } elseif (strpos($token, 'Errore') !== false) {
            return response()->json(['message' =>  $token]);
        } else {
            return response()->json(['message' => 'Autenticazione riuscita, token: ' . $token], 400);
        }
    }

    public function testFattura24Api(Request $request)
    {
        // Chiamata al servizio per testare l'API di Fattura24
        $message = $this->fattura24Service->testKey();

        // Restituisci la risposta in formato JSON per gestirla con AJAX
        return response()->json(['message' => $message]);
    }
}
