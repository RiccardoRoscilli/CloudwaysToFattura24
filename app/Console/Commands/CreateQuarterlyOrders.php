<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Models\Order;
use Carbon\Carbon;

class CreateQuarterlyOrders extends Command
{
    protected $signature = 'orders:create-quarterly';
    protected $description = 'Crea ordini trimestrali per le applicazioni';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentDate = Carbon::now();
        $quarterStart = Carbon::createFromDate($currentDate->year, ceil($currentDate->month / 3) * 3 - 2, 1);
        $quarterEnd = $quarterStart->copy()->addMonths(3)->subDay();

        // Recupera tutte le applicazioni e crea un ordine per ciascuna
        $applications = Application::all();

        foreach ($applications as $application) {
            Order::create([
                'application_id' => $application->id,
                'start_date' => $quarterStart,
                'end_date' => $quarterEnd,
                'amount' => $this->calculateAmount($application)
            ]);
        }

        $this->info('Ordini trimestrali creati con successo.');
    }

    private function calculateAmount($application)
    {
        return 100;  // Logica per calcolare il totale dell'ordine
    }
}
