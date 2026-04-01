<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;

echo "=== CREAZIONE ORDINE DOMINI ELDA + SPOSTAMENTO CLIENTE ===\n\n";

$oldCustomerId = 88;  // Lava & Asciuga di Zanoli Elda
$newCustomerId = 228; // Le Essenze di Elda srl
$startDate = '2026-03-11';
$endDate = '2027-03-11';

// Domini da spostare (quelli leessenzedielda.*)
$domainIds = [160, 161, 162, 163, 164, 165, 166, 167]; // leessenzedielda.*

$services = DB::table('services')->whereIn('id', $domainIds)->where('is_active', 1)->get();

echo "1. Spostamento domini da cliente {$oldCustomerId} a {$newCustomerId}\n";
foreach ($services as $s) {
    echo "   {$s->service_name} (ID {$s->id})\n";
}

DB::table('services')->whereIn('id', $domainIds)->update(['customer_id' => $newCustomerId]);
echo "   ✅ " . count($domainIds) . " domini spostati\n\n";

// Sposta anche le mailbox leessenzedielda.com
$mailboxMoved = DB::table('mailboxes')
    ->where('mailbox_email', 'like', '%@leessenzedielda.com')
    ->update(['customer_id' => $newCustomerId]);
echo "2. Mailbox leessenzedielda.com spostate: {$mailboxMoved}\n\n";

// Crea ordine per il nuovo cliente
echo "3. Creazione ordine per cliente {$newCustomerId}\n";

$order = Order::create([
    'customer_id' => $newCustomerId,
    'amount' => $services->sum('price'),
    'start_date' => $startDate,
    'end_date' => $endDate,
    'payment_type' => 'bonifico',
    'status' => 'in_progress',
]);

echo "   Ordine ID: {$order->id} | Totale: {$services->sum('price')}€\n";

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
    echo "   ✅ {$s->service_name} → {$s->price}€\n";
}

// Aggiorna scadenze
DB::table('services')->whereIn('id', $domainIds)->update(['expiry_date' => $endDate]);
echo "\n4. Scadenze aggiornate a {$endDate}\n";

echo "\n✅ Completato! Ordine {$order->id} pronto per invio a Fattura24.\n";
