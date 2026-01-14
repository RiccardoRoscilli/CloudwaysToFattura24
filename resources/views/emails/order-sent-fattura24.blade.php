<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ordine inviato a Fattura24</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .info-row {
            margin: 10px 0;
            padding: 10px;
            background-color: white;
            border-left: 3px solid #4CAF50;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✓ Ordine Inviato a Fattura24</h2>
        </div>
        <div class="content">
            <p>L'ordine è stato inviato con successo a Fattura24.</p>
            
            <div class="info-row">
                <span class="label">Ordine ID:</span> #{{ $order->id }}
            </div>
            
            <div class="info-row">
                <span class="label">Cliente:</span> {{ $order->customer->name ?? 'N/A' }}
            </div>
            
            <div class="info-row">
                <span class="label">Importo:</span> €{{ number_format($order->amount, 2, ',', '.') }}
            </div>
            
            <div class="info-row">
                <span class="label">Documento Fattura24 ID:</span> {{ $docId }}
            </div>
            
            <div class="info-row">
                <span class="label">Numero Documento:</span> {{ $docNumber }}
            </div>
            
            <div class="info-row">
                <span class="label">Data invio:</span> {{ now()->format('d/m/Y H:i:s') }}
            </div>
            
            <p style="margin-top: 20px; color: #666;">
                Questo è un messaggio automatico generato dal sistema.
            </p>
        </div>
    </div>
</body>
</html>
