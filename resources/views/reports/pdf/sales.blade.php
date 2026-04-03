<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report — {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }

        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #4f46e5; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 3px 0 0; color: #555; font-size: 11px; }

        .info { width: 100%; margin-bottom: 20px; }
        .info td { vertical-align: top; border: none; }
        .info-left  { width: 50%; }
        .info-right { width: 50%; text-align: right; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f3f4f6; color: #374151; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 10px 8px; text-align: left; }
        .text-right { text-align: right; }
        .font-bold  { font-weight: bold; }

        .summary-row { background-color: #1f2937; color: white; font-weight: bold; }

        .stat-block { display: inline-block; margin-right: 20px; }
        .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        .stat-value { font-size: 14px; font-weight: bold; color: #1f2937; }

        .profit { color: #059669; }
        .amount { color: #4f46e5; }
        .paid   { color: #2563eb; }

        .footer { margin-top: 50px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 10px; color: #888; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Sales Report</h1>
        <p>{{ config('app.name', 'Trading ERP') }}</p>
    </div>

    <!-- Info Block -->
    <table class="info" style="border: none;">
        <tr>
            <td class="info-left" style="border:none;">
                <div class="font-bold" style="font-size: 14px;">Sales Summary</div>
                <div>Total Invoices: <strong>{{ $sales->count() }}</strong></div>
                <div>Service Revenue: <strong>{{ number_format($summary['service_revenue'], 2) }}</strong></div>
                <div>Service Profit: <strong class="profit">{{ number_format($summary['service_profit'], 2) }}</strong></div>
            </td>
            <td class="info-right" style="border:none;">
                <div><strong>Period:</strong> {{ $startDate }} to {{ $endDate }}</div>
                <div><strong>Generated:</strong> {{ now()->format('d-M-Y H:i') }}</div>
                <div class="font-bold" style="color: #059669; font-size: 14px; margin-top: 5px;">
                    Total Profit: {{ number_format($summary['total_profit'], 2) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Sales Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 13%;">Date</th>
                <th style="width: 22%;">Invoice #</th>
                <th style="width: 35%;">Customer</th>
                <th style="width: 15%;" class="text-right">Amount</th>
                <th style="width: 15%;" class="text-right">Profit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}</td>
                <td class="font-bold" style="color: #4f46e5;">{{ $sale->invoice_no }}</td>
                <td>{{ $sale->client->name }}</td>
                <td class="text-right amount font-bold">{{ number_format($sale->total_amount, 2) }}</td>
                <td class="text-right profit font-bold">{{ number_format($sale->total_profit, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #9ca3af; padding: 20px;">No sales found for this period.</td>
            </tr>
            @endforelse

            <tr class="summary-row">
                <td colspan="3">TOTALS — {{ $sales->count() }} Invoice(s)</td>
                <td class="text-right">{{ number_format($summary['total_sales'], 2) }}</td>
                <td class="text-right">{{ number_format($summary['total_profit'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        This is a computer-generated document. No signature is required.
    </div>
</body>
</html>
