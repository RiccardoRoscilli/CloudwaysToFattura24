<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Report Giornaliero Fattura24</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
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
        .summary {
            background-color: #f0f8ff;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }
        .summary-item {
            font-size: 18px;
            margin: 10px 0;
        }
        .summary-item strong {
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
        }
        .no-orders {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📊 Report Trimestrale Fattura24</h2>
            <p>{{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="summary">
            <div class="summary-item">
                <strong>Ordini processati:</strong> {{ $orders->count() }}
            </div>
            <div class="summary-item">
                <strong>Totale fatturato:</strong> € {{ number_format($totalAmount, 2, ',', '.') }}
            </div>
        </div>

        @if($orders->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID Ordine</th>
                        <th>Cliente</th>
                        <th>Importo</th>
                        <th>Data Invio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                            <td>€ {{ number_format($order->amount, 2, ',', '.') }}</td>
                            <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-orders">
                <p>Nessun ordine inviato a Fattura24 in questo trimestre.</p>
            </div>
        @endif

        <div class="footer">
            <p>Questo è un report automatico generato dal sistema Hosting App</p>
            <p>Report generato il {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
