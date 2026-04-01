<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Mailbox;
use App\Models\Customer;

echo "=== IMPORT MAILBOX DA XLSX ===\n\n";

$file = __DIR__ . '/860613_Requested_Details.xlsx';
$zip = new ZipArchive;
$zip->open($file);
$ss = $zip->getFromName('xl/sharedStrings.xml');
$sd = $zip->getFromName('xl/worksheets/sheet1.xml');
$zip->close();

$strings = [];
$xml = simplexml_load_string($ss);
foreach ($xml->si as $si) {
    $t = '';
    if (isset($si->t)) $t = (string)$si->t;
    elseif (isset($si->r)) { foreach ($si->r as $r) $t .= (string)$r->t; }
    $strings[] = $t;
}

$xml = simplexml_load_string($sd);
$rows = [];
foreach ($xml->sheetData->row as $row) {
    $cells = [];
    foreach ($row->c as $cell) {
        $ref = (string)$cell['r'];
        preg_match('/^([A-Z]+)/', $ref, $m);
        $col = $m[1];
        $val = (string)$cell->v;
        if (isset($cell['t']) && $cell['t'] == 's') $val = $strings[intval($val)] ?? $val;
        $cells[$col] = $val;
    }
    $rows[] = $cells;
}

// Mappa dominio -> customer_id dalle mailbox esistenti
$domainMap = [];
$existing = Mailbox::whereNotNull('customer_id')->get();
foreach ($existing as $mb) {
    $domain = substr($mb->mailbox_email, strpos($mb->mailbox_email, '@') + 1);
    if ($mb->customer_id) $domainMap[$domain] = $mb->customer_id;
}

echo "Domini con cliente: " . count($domainMap) . "\n\n";

$stats = ['created' => 0, 'skipped' => 0, 'no_customer' => 0];

// Skip header (row 0)
for ($i = 1; $i < count($rows); $i++) {
    $row = $rows[$i];
    $email = trim($row['B'] ?? '');
    $enabled = $row['C'] ?? '0';

    if (empty($email)) continue;
    if ($enabled != '1') continue;

    // Skip se esiste già
    if (Mailbox::where('mailbox_email', $email)->exists()) {
        $stats['skipped']++;
        continue;
    }

    $domain = substr($email, strpos($email, '@') + 1);
    $customerId = $domainMap[$domain] ?? null;

    Mailbox::create([
        'mailbox_email' => $email,
        'server' => 'secure.emailsrvr.com',
        'IMAPport' => 993,
        'SMTPport' => 465,
        'customer_id' => $customerId,
    ]);

    if ($customerId) {
        echo "✅ {$email} → cliente ID {$customerId}\n";
    } else {
        echo "⚠️  {$email} → nessun cliente (dominio {$domain})\n";
        $stats['no_customer']++;
    }
    $stats['created']++;
}

echo "\n=== RIEPILOGO ===\n";
echo "Create: {$stats['created']}\n";
echo "Già esistenti: {$stats['skipped']}\n";
echo "Senza cliente: {$stats['no_customer']}\n";
echo "\n✅ Completato!\n";
