<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Application;
use App\Models\Service;
use App\Models\Mailbox;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Carbon\Carbon;
use App\Http\Controllers\OrderController;


class CreateQuarterlyOrders extends Command
{
    protected $signature = 'orders:create-quarterly';
    protected $description = 'Crea ordini trimestrali per i clienti con le loro applicazioni, servizi e mailbox';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
         
        $currentDate = Carbon::now();
        $currentQuarterStart = Carbon::create($currentDate->year, ceil($currentDate->month / 3) * 3 - 2, 1);
        $currentQuarterEnd = $currentQuarterStart->copy()->addMonths(3)->subDay();

        $totalOrdersCreated = 0;

        $customers = Customer::with(['applications', 'services', 'mailBoxes'])->get();
        
        foreach ($customers as $customer) {
            
            $orderItems = [];

            // Aggiungi applicazioni come OrderItem
            foreach ($customer->applications as $application) {
                if (!$application->is_active) {
                    continue;
                }

                $existingOrderItem = OrderItem::where('service_type', 'application')
                    ->where('service_id', $application->id)
                    ->where('start_date', $currentQuarterStart)
                    ->where('end_date', $currentQuarterEnd)
                    ->exists();

                if ($existingOrderItem) {
                    $this->info("Ordine già esistente per l'applicazione {$application->id} del cliente {$customer->id}.");
                    continue;
                }

                $orderItems[] = new OrderItem([
                    'service_type' => 'application',
                    'service_id' => $application->id,
                    'price' => $application->price ?? 0,
                    'billing_frequency' => 'quarterly',
                    'start_date' => $currentQuarterStart,
                    'end_date' => $currentQuarterEnd,
                ]);
            }

            // Aggiungi servizi come OrderItem
            foreach ($customer->services as $service) {
                if (!$service->is_active) {
                    continue;
                }

                if (in_array($service->service_type, ['dominio', 'hosting']) && $service->expiry_date) {
                    $expiryDate = Carbon::parse($service->expiry_date);

                    // Controlla se la scadenza è all'interno del trimestre corrente
                    if ($expiryDate->between($currentQuarterStart, $currentQuarterEnd)) {
                        $nextYearEnd = $expiryDate->copy()->addYear();

                        $existingOrderItem = OrderItem::where('service_type', $service->service_type)
                            ->where('service_id', $service->id)
                            ->where('start_date', $expiryDate)
                            ->where('end_date', $nextYearEnd)
                            ->exists();

                        if ($existingOrderItem) {
                            $this->info("Ordine già esistente per il servizio {$service->id} del cliente {$customer->id}.");
                            continue;
                        }

                        $orderItems[] = new OrderItem([
                            'service_type' => $service->service_type,
                            'service_id' => $service->id,
                            'price' => $service->price ?? 0,
                            'billing_frequency' => 'annual',
                            'start_date' => $expiryDate,
                            'end_date' => $nextYearEnd,
                        ]);
                    } else {
                        $this->info("Il servizio {$service->id} del cliente {$customer->id} non scade nel trimestre corrente.");
                    }
                } elseif ($service->billing_frequency === 'quarterly') {
                    $existingOrderItem = OrderItem::where('service_type', 'service')
                        ->where('service_id', $service->id)
                        ->where('start_date', $currentQuarterStart)
                        ->where('end_date', $currentQuarterEnd)
                        ->exists();

                    if ($existingOrderItem) {
                        $this->info("Ordine già esistente per il servizio {$service->id} del cliente {$customer->id}.");
                        continue;
                    }

                    // Calcola il costo per il trimestre
                    $quarterlyPrice = ($service->price ?? 0) * 3;

                    $orderItems[] = new OrderItem([
                        'service_type' => 'service',
                        'service_id' => $service->id,
                        'price' => $quarterlyPrice,
                        'billing_frequency' => 'quarterly',
                        'start_date' => $currentQuarterStart,
                        'end_date' => $currentQuarterEnd,
                    ]);
                }
            }


            // Aggiungi mailbox come OrderItem
            foreach ($customer->mailBoxes as $mailbox) {
                // Controlla se l'OrderItem esiste già
                $existingOrderItem = OrderItem::where('service_type', 'mailbox')
                    ->where('service_id', $mailbox->id)
                    ->where('start_date', $currentQuarterStart)
                    ->where('end_date', $currentQuarterEnd)
                    ->exists();

                if ($existingOrderItem) {
                    $this->info("Ordine già esistente per la mailbox {$mailbox->id} del cliente {$customer->id}.");
                    continue;
                }

                $orderItems[] = new OrderItem([
                    'service_type' => 'mailbox',
                    'service_id' => $mailbox->id,
                    'price' => 3, // Prezzo fisso di 3 euro al mese
                    'billing_frequency' => 'quarterly',
                    'start_date' => $currentQuarterStart,
                    'end_date' => $currentQuarterEnd,
                ]);
            }


            // Crea l'ordine solo se ci sono OrderItems
            if (!empty($orderItems)) {
                $order = Order::create([
                    'status' => 'pending',
                    'amount' => 0, // Calcolato dopo
                    'customer_id' => $customer->id,
                ]);

                $totalAmount = 0;

                foreach ($orderItems as $item) {
                    $item->order_id = $order->id;
                    $item->save();
                    $totalAmount += $item->price * ($item->billing_frequency === 'quarterly' ? 3 : 1);
                }

                $order->update(['amount' => $totalAmount]);
                $totalOrdersCreated++;

                // Invia l'ordine a Fattura24
                try {
                    (new OrderController())->sendToFattura24($order->id);
                    $this->info("Ordine {$order->id} inviato a Fattura24.");
                } catch (\Exception $e) {
                    $this->error("Errore nell'invio dell'ordine {$order->id}: " . $e->getMessage());
                }
            } else {
                $this->info("Nessun ordine creato per il cliente {$customer->id}.");
            }
        }

        $this->info("Creati {$totalOrdersCreated} ordini per i clienti attivi.");
    }

}
