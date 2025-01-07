<!DOCTYPE html>
<html>

<head>
    <title>Dettagli Ordine</title>
    <style>
        /* Stili inline per compatibilità con i client di posta */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        h1,
        h2 {
            font-family: Arial, sans-serif;
        }

        p {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <h1>Spettabile {{ $customer->name }}</h1>
    <p>abbiamo emesso un ordine automatico per i servizi di seguito elencati:</p>

    <h2>Dettagli Ordine {{ $orderId }}</h2>
    <table>
        <thead>
            <tr>
                <th>Descrizione</th>
                <th>Tipo</th>
                <th>Quantità</th>
                <th>Prezzo</th>
                <th>Totale</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        @if ($item->service_type === 'application')
                            {{ $item->application->label ?? 'N/A' }}
                        @elseif ($item->service_type === 'mailbox')
                            {{ $item->mailbox->email ?? 'N/A' }}
                        @else
                            {{ $item->service->service_name ?? 'N/A' }}
                        @endif
                    </td>
                    <td>{{ ucfirst($item->service_type) }}</td>
                    <td>{{ $item->billing_frequency === 'quarterly' ? 3 : 1 }}</td>
                    <td>€ {{ number_format($item->price, 2) }}</td>
                    <td>€ {{ number_format($item->price * ($item->billing_frequency === 'quarterly' ? 3 : 1), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @php
        $vatRate = 0.22; // Aliquota IVA 22%
        $vatAmount = $order->amount * $vatRate;
        $totalWithVat = $order->amount + $vatAmount;
    @endphp
    <p><strong>Totale Ordine (con IVA):</strong> € {{ number_format($totalWithVat, 2) }}</p>
    <p><strong>IBAN per il pagamento:</strong> {{ $paymentDetails }}</p>
    <p><strong>Conto Intestato a: Roscilli Riccardo</p>
    <p><strong>Causale: Saldo ordine {{ $orderId }} </p>

    <p>Per favore, proceda al pagamento utilizzando i dettagli sopra indicati.</p>
    <p>Una volta ricevuto il pagamento, verrà emessa la fattura elettronica.</p>
    <p>Cordiali Saluti,<br>PWS Staff</p>
</body>

</html>
