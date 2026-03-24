<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Ledger - {{ $client->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #4f46e5; text-transform: uppercase; letter-spacing: 2px; }
        .info { width: 100%; margin-bottom: 20px; }
        .info td { vertical-align: top; }
        .client-info { width: 50%; }
        .report-info { width: 50%; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f3f4f6; color: #374151; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 10px 8px; text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .debit { color: #dc2626; } /* Receivable Increases */
        .credit { color: #059669; } /* Receivable Decreases */
        .summary { width: 40%; margin-left: auto; border: 2px solid #374151; }
        .summary-row { background-color: #1f2937; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CUSTOMER LEDGER REPORT</h1>
        <p>{{ config('app.name', 'Yarn Trading ERP') }}</p>
    </div>

    <table class="info" style="border: none;">
        <tr style="border: none;">
            <td class="client-info" style="border: none;">
                <div class="font-bold" style="font-size: 14px;">{{ $client->name }}</div>
                <div>{{ $client->phone }}</div>
                <div>{{ $client->address }}</div>
            </td>
            <td class="report-info" style="border: none;">
                <div><strong>Period:</strong> {{ $from ?? 'Start' }} to {{ $to ?? 'Today' }}</div>
                <div><strong>Generated:</strong> {{ now()->format('d-M-Y H:i') }}</div>
                <div class="font-bold" style="color: #4f46e5; font-size: 14px; margin-top: 5px;">{{ $client->outstandingBalanceLabel() }}</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 45%;">Description</th>
                <th style="width: 13%;" class="text-right">Debit</th>
                <th style="width: 13%;" class="text-right">Credit</th>
                <th style="width: 14%;" class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold italic">{{ $from ?? '-' }}</td>
                <td class="font-bold italic">Opening Balance</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right font-bold">{{ number_format($openingBalance, 2) }}</td>
            </tr>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ date('d-m-Y', strtotime($row['date'])) }}</td>
                    <td>{{ $row['description'] }}</td>
                    <td class="text-right debit">{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '-' }}</td>
                    <td class="text-right credit">{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '-' }}</td>
                    <td class="text-right font-bold">{{ number_format($row['balance'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="summary-row font-bold">
                <td colspan="4">CLOSING BALANCE</td>
                <td class="text-right">{{ number_format($closingBalance, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 10px; color: #888;">
        This is a computer-generated document. No signature is required.
    </div>
</body>
</html>
