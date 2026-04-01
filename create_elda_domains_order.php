<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;

echo "=== CREAZIONE ORDINE DOMINI ELDA ===\n\n";

$customerId = 88;
$startDate = '2026-03-11';
$endDate = '2027-03-11';

$services = DB::table('services')
    ->where('customer_id', $customerId)
    ->where('service_type', 'dominio')
    ->where('is_active', 1)
    ->get();

echo "Domini trovati: " . $services->count() . "\n";
echo "Totale: " . $services->sum('price') . "€\n\n";

// Crea ordine
$order = Order::create([
    'customer_id' => $customerId,
    'amount' => $services->sum('price'),
    'start_date' => $startDate,
    'end_date' => $endDate,
    'payment_type' => 'bonifico',
    'status' => 'in_progress',
]);

echo "Ordine creato: ID " . $order->id . "\n\n";

foreach ($services as $s) {
    OrderItem::create([
        'order_id' => $order->id,
        'service_type' => 'dominio',
        'service_id' => $s->id,
        'price' => $s->price,
        'billing_frequency' => 'annual',
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]);
    echo "  ✅ {$s->service_name} → {$s->price}€\n";
}

// Aggiorna scadenze
DB::table('services')
    ->where('customer_id', $customerId)
    ->where('service_type', 'dominio')
    ->where('is_active', 1)
    ->update(['expiry_date' => $endDate]);

echo "\n✅ Scadenze aggiornate a {$endDate}\n";
echo "✅ Ordine " . $order->id . " creato. Invialo a Fattura24 dall'interfaccia.\n";
