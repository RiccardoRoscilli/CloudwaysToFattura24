<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyFattura24Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fattura24:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invia un report trimestrale degli ordini inviati a Fattura24';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Recupera gli ordini completati nell'ultimo giorno (dopo il cron trimestrale)
        $orders = Order::with('customer')
            ->where('status', 'complete')
            ->where('updated_at', '>=', Carbon::now()->subDay())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Nessun ordine inviato a Fattura24 nell\'ultimo giorno.');
            return 0;
        }

        // Recupera l'admin
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->error('Admin non trovato.');
            return 1;
        }

        // Invia la mail di riepilogo
        try {
            Mail::to($admin->email)->send(new \App\Mail\DailyFattura24Report($orders));
            $this->info("Report inviato con successo a {$admin->email}. Ordini processati: {$orders->count()}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Errore durante l\'invio del report: ' . $e->getMessage());
            \Log::error('Errore invio report Fattura24:', ['error' => $e->getMessage()]);
            return 1;
        }
    }
}
