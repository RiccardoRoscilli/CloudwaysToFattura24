<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CloudwaysService;
use App\Models\Server;
use App\Models\Application;

class CloudwaysController extends Controller
{
    protected $cloudwaysService;

    public function __construct(CloudwaysService $cloudwaysService)
    {
        $this->cloudwaysService = $cloudwaysService;
    }
    // Metodo per recuperare e inserire i server e le applicazioni
    public function importServersAndApps()
    {
        $email = config('services.cloudways.email');
        $apiKey = config('services.cloudways.api_key');

        // Autenticazione per ottenere il token
        $accessToken = $this->cloudwaysService->authenticate($email, $apiKey);

        if (!$accessToken) {
            return response()->json(['error' => 'Autenticazione fallita'], 401);
        }

        // Ottieni i server con le relative applicazioni
        $serversWithApplications = $this->cloudwaysService->getServersWithApplications($accessToken);

        // Chiama il metodo per inserire i dati
        $this->storeServersAndApps($serversWithApplications);

        return response()->json(['success' => 'Server e applicazioni importati con successo']);
    }

    // Metodo per salvare i server e le applicazioni nel database
    private function storeServersAndApps($data)
    {
        foreach ($data['servers'] as $serverData) {
            if(!$serverData['id']) dd($serverData);
            // Usa updateOrCreate per il server con l'ID di Cloudways
            $server = Server::updateOrCreate(
                [
                    'cloudways_server_id' => $serverData['id'], // ID univoco del server di Cloudways
                ],
                [
                    'cloudways_server_id' => $serverData['id'],
                    'label' => $serverData['label'],
                    'status' => $serverData['status'],
                    'tenant_id' => $serverData['tenant_id'],
                    'backup_frequency' => $serverData['backup_frequency'],
                    'backup_retention' => $serverData['backup_retention'],
                    'local_backups' => $serverData['local_backups'],
                    'backup_time' => $serverData['backup_time'],
                    'is_terminated' => $serverData['is_terminated'],
                    'created_at' => $serverData['created_at'],
                    'updated_at' => $serverData['updated_at'],
                    'platform' => $serverData['platform'],
                    'cloud' => $serverData['cloud'],
                    'region' => $serverData['region'],
                    'instance_type' => $serverData['instance_type'],
                    'server_fqdn' => $serverData['server_fqdn'],
                    'public_ip' => $serverData['public_ip'],
                    'volume_size' => $serverData['volume_size'],
                    'master_user' => $serverData['master_user'],
                    'master_password' => $serverData['master_password'],
                ]
            );

            // Inserisci o aggiorna le applicazioni associate
            foreach ($serverData['apps'] as $appData) {
               
                Application::updateOrCreate(
                    [
                        'cloudways_app_id' => $appData['id'], // ID univoco dell'application di Cloudways
                    ],
                    [
                        'cloudways_app_id' => $appData['id'],
                        'label' => $appData['label'],
                        'application' => $appData['application'],
                        'app_version' => $appData['app_version'],
                        'app_fqdn' => $appData['app_fqdn'],
                        'sys_user' => $appData['sys_user'],
                        'cname' => $appData['cname'] ?? null,
                        'server_id' => $server->id, // Relazione con il server
                        'created_at' => $appData['created_at'],
                        'app_user' => $appData['app_user'] ?? null,
                        'app_password' => $appData['app_password'] ?? null,
                        'sys_password' => $appData['sys_password'] ?? null,
                        'mysql_db_name' => $appData['mysql_db_name'] ?? null,
                        'mysql_user' => $appData['mysql_user'] ?? null,
                        'mysql_password' => $appData['mysql_password'] ?? null,
                        'webroot' => $appData['webroot'] ?? null,
                        'is_csr_available' => $appData['is_csr_available'] ?? false,
                        'lets_encrypt' => json_encode($appData['lets_encrypt']) ?? null,
                    ]
                );
            }
        }
    }

    public function getServers()
    {


        // Autenticazione per ottenere il token
        $accessToken = $this->cloudwaysService->authenticate();

        if (!$accessToken) {
            return response()->json(['error' => 'Autenticazione fallita'], 401);
        }

        // Ottieni la lista dei server con le applicazioni
        $serversWithApplications = $this->cloudwaysService->getServersWithApplications($accessToken);

        // Verifica se ci sono errori o se i server sono vuoti
        if (isset($serversWithApplications['servers']) && !empty($serversWithApplications['servers'])) {
            return response()->json($serversWithApplications['servers']);
        } else {
            return response()->json(['error' => 'Nessun server trovato'], 404);
        }
    }

    public function importServices()
    {
        // Ottieni la chiave API e l'email dal file .env
        $email = env('CLOUDWAYS_EMAIL');
        $apiKey = env('CLOUDWAYS_API_KEY');

        // Ottieni il token di accesso
        $accessToken = $this->cloudwaysService->authenticate($email, $apiKey);

        if (!$accessToken) {
            return response()->json(['error' => 'Autenticazione fallita con Cloudways'], 401);
        }

        // Recupera i servizi dall'API
        $services = $this->cloudwaysService->getServices($accessToken);

        // Aggiungi logica per salvare i servizi nel database, se necessario

        return response()->json($services);
    }
}
