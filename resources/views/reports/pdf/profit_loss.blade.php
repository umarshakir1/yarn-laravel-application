<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit &amp; Loss Report — {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }

        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #4f46e5; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 3px 0 0; color: #555; font-size: 11px; }

        .info { width: 100%; margin-bottom: 20px; }
        .info td { vertical-align: top; border: none; }
        .info-left  { width: 50%; }
        .info-right { width: 50%; text-align: right; }

        /* KPI row — 3 boxes side by side using a borderless table */
        .kpi-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .kpi-table td { border: 1px solid #e5e7eb; padding: 12px 14px; text-align: center; background-color: #f9fafb; }
        .kpi-label { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #6b7280; margin-bottom: 4px; }
        .kpi-value { font-size: 18px; font-weight: bold; }
        .kpi-sub   { font-size: 10px; color: #6b7280; margin-top: 4px; }
        .kpi-profit   { color: #059669; }
        .kpi-expense  { color: #dc2626; }
        .kpi-net-pos  { color: #4f46e5; }
        .kpi-net-neg  { color: #dc2626; }

        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
                         color: #374151; background-color: #f3f4f6; border: 1px solid #e5e7eb;
                         border-bottom: none; padding: 8px 10px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f3f4f6; color: #374151; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 10px 8px; text-align: left; }
        .text-right { text-align: right; }
        .font-bold  { font-weight: bold; }

        .summary-row         { background-color: #1f2937; color: white; font-weight: bold; }
        .summary-row-indigo  { background-color: #4338ca; color: white; font-weight: bold; }

        .profit { color: #059669; font-weight: bold; }
        .loss   { color: #dc2626; font-weight: bold; }
        .cost   { color: #dc2626; }
        .revenue { color: #4f46e5; font-weight: bold; }

        .footer { margin-top: 50px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 10px; color: #888; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Profit &amp; Loss Report</h1>
        <p>{{ config('app.name', 'Trading ERP') }}</p>
    </div>

    <!-- Info Block -->
    <table class="info" style="border: none;">
        <tr>
            <td class="info-left" style="border:none;">
                <div class="font-bold" style="font-size: 14px;">Financial Performance</div>
                <div>Lots / Products Sold: <strong>{{ $lotBreakdown->count() }}</strong></div>
                <div>Services Rendered: <strong>{{ $serviceBreakdown->count() }}</strong></div>
            </td>
            <td class="info-right" style="border:none;">
                <div><strong>Period:</strong> {{ $startDate }} to {{ $endDate }}</div>
                <div><strong>Generated:</strong> {{ now()->format('d-M-Y H:i') }}</div>
                <div class="font-bold {{ $netProfit >= 0 ? 'kpi-net-pos' : 'kpi-net-neg' }}" style="font-size: 14px; margin-top: 5px;">
                    Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}: {{ number_format(abs($netProfit), 2) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- KPI Summary -->
    <table class="kpi-table">
        <tr>
            <td>
                <div class="kpi-label">Gross Profit</div>
                <div class="kpi-value kpi-profit">{{ number_format($totalSaleProfit, 2) }}</div>
                <div class="kpi-sub">Products: {{ number_format($totalSaleProfit - $serviceProfit, 2) }} | Services: {{ number_format($serviceProfit, 2) }}</div>
            </td>
            <td>
                <div class="kpi-label">Total Expenses</div>
                <div class="kpi-value kpi-expense">{{ number_format($totalExpenses, 2) }}</div>
                <div class="kpi-sub">Operational costs in this period</div>
            </td>
            <td>
                <div class="kpi-label">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</div>
                <div class="kpi-value {{ $netProfit >= 0 ? 'kpi-net-pos' : 'kpi-net-neg' }}">{{ number_format(abs($netProfit), 2) }}</div>
                <div class="kpi-sub">{{ $netProfit >= 0 ? '✓ Profitable Period' : '⚠ Net Loss Period' }}</div>
            </td>
        </tr>
    </table>

    <!-- Product / Lot Breakdown -->
    @if($lotBreakdown->isNotEmpty())
    <div class="section-title">Product / Lot Profit Breakdown</div>
    <table>
        <thead>
            <tr>
                <th style="width: 14%;">Lot #</th>
                <th style="width: 22%;">Product</th>
                <th style="width: 10%;" class="text-right">Bundles</th>
                <th style="width: 11%;" class="text-right">Cost/Bundle</th>
                <th style="width: 11%;" class="text-right">Avg Sale/Bundle</th>
                <th style="width: 11%;" class="text-right">Revenue</th>
                <th style="width: 10%;" class="text-right">Cost</th>
                <th style="width: 11%;" class="text-right">Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lotBreakdown as $row)
            <tr>
                <td class="font-bold" style="color: #4f46e5;">{{ $row->lot_number }}</td>
                <td>{{ $row->product_name }} <span style="color: #9ca3af;">({{ $row->product_quality }})</span></td>
                <td class="text-right">{{ number_format($row->total_bundles, 2) }}</td>
                <td class="text-right cost">{{ number_format($row->cost_price, 2) }}</td>
                <td class="text-right" style="color: #2563eb;">{{ number_format($row->avg_sale_price, 2) }}</td>
                <td class="text-right revenue">{{ number_format($row->total_revenue, 2) }}</td>
                <td class="text-right cost">{{ number_format($row->total_cost, 2) }}</td>
                <td class="text-right {{ $row->total_profit >= 0 ? 'profit' : 'loss' }}">{{ number_format($row->total_profit, 2) }}</td>
            </tr>
            @endforeach
            <tr class="summary-row">
                <td colspan="5">PRODUCT TOTALS</td>
                <td class="text-right">{{ number_format($lotBreakdown->sum('total_revenue'), 2) }}</td>
                <td class="text-right">{{ number_format($lotBreakdown->sum('total_cost'), 2) }}</td>
                <td class="text-right">{{ number_format($lotBreakdown->sum('total_profit'), 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Service Breakdown -->
    @if($serviceBreakdown->isNotEmpty())
    <div class="section-title" style="background-color: #ede9fe; color: #4338ca;">Service Profit Breakdown</div>
    <table>
        <thead>
            <tr>
                <th style="width: 46%;">Service Name</th>
                <th style="width: 18%;" class="text-right">Revenue</th>
                <th style="width: 18%;" class="text-right">Cost</th>
                <th style="width: 18%;" class="text-right">Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($serviceBreakdown as $row)
            <tr>
                <td class="font-bold">{{ $row->service_name }}</td>
                <td class="text-right" style="color: #4f46e5;">{{ number_format($row->total_revenue, 2) }}</td>
                <td class="text-right cost">{{ number_format($row->total_cost, 2) }}</td>
                <td class="text-right {{ $row->total_profit >= 0 ? 'profit' : 'loss' }}">{{ number_format($row->total_profit, 2) }}</td>
            </tr>
            @endforeach
            <tr class="summary-row-indigo">
                <td>SERVICE TOTALS</td>
                <td class="text-right">{{ number_format($serviceBreakdown->sum('total_revenue'), 2) }}</td>
                <td class="text-right">{{ number_format($serviceBreakdown->sum('total_cost'), 2) }}</td>
                <td class="text-right">{{ number_format($serviceBreakdown->sum('total_profit'), 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="footer">
        This is a computer-generated document. No signature is required.
    </div>
</body>
</html>
