<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }

        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #059669; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #059669; text-transform: uppercase; letter-spacing: 2px; }
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
        .low-stock   { color: #dc2626; font-weight: bold; }
        .value       { color: #059669; font-weight: bold; }

        .footer { margin-top: 50px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 10px; color: #888; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Inventory Report</h1>
        <p>{{ config('app.name', 'Trading ERP') }}</p>
    </div>

    <!-- Info Block -->
    @php $totalValue = $lots->sum(fn($l) => $l->remaining_bags * 5 * $l->cost_price_per_bundle); @endphp
    <table class="info" style="border: none;">
        <tr>
            <td class="info-left" style="border:none;">
                <div class="font-bold" style="font-size: 14px;">Stock Snapshot</div>
                <div>Total Lots: <strong>{{ $lots->count() }}</strong></div>
                <div>Total Remaining Bags: <strong>{{ number_format($lots->sum('remaining_bags'), 2) }}</strong></div>
                @if($startDate || $endDate)
                    <div style="color: #6b7280; font-size: 11px;">Filtered by purchase date</div>
                @endif
            </td>
            <td class="info-right" style="border:none;">
                @if($startDate || $endDate)
                    <div><strong>Period:</strong> {{ $startDate ?? 'Start' }} to {{ $endDate ?? 'Today' }}</div>
                @else
                    <div><strong>Snapshot:</strong> All Available Stock</div>
                @endif
                <div><strong>Generated:</strong> {{ now()->format('d-M-Y H:i') }}</div>
                <div class="font-bold value" style="font-size: 14px; margin-top: 5px;">
                    Total Value: {{ number_format($totalValue, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Inventory Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 18%;">Lot Number</th>
                <th style="width: 28%;">Product</th>
                <th style="width: 11%;" class="text-right">Init. Bags</th>
                <th style="width: 11%;" class="text-right">Rem. Bags</th>
                <th style="width: 10%;" class="text-right">Bundles</th>
                <th style="width: 10%;" class="text-right">Rem. KGs</th>
                <th style="width: 10%;" class="text-right">Cost/Bundle</th>
                <th style="width: 9%;" class="text-right">Value</th>
            </tr>
        </thead>
        <tbody>
            @php $runningTotal = 0; @endphp
            @forelse($lots as $lot)
            @php
                $v = $lot->remaining_bags * 5 * $lot->cost_price_per_bundle;
                $runningTotal += $v;
                $isLow = $lot->remaining_bags < 10;
            @endphp
            <tr>
                <td class="font-bold" style="color: #4f46e5;">{{ $lot->lot_number }}</td>
                <td>{{ $lot->product->name }} <span style="color: #9ca3af;">({{ $lot->product->quality }})</span></td>
                <td class="text-right">{{ number_format($lot->initial_bags, 2) }}</td>
                <td class="text-right {{ $isLow ? 'low-stock' : '' }}">
                    {{ number_format($lot->remaining_bags, 2) }}{{ $isLow ? ' ⚠' : '' }}
                </td>
                <td class="text-right">{{ number_format($lot->remaining_bags * 5, 2) }}</td>
                <td class="text-right">{{ number_format($lot->kg_quantity ?? ($lot->remaining_bags * 25), 2) }}</td>
                <td class="text-right">{{ number_format($lot->cost_price_per_bundle, 2) }}</td>
                <td class="text-right value">{{ number_format($v, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #9ca3af; padding: 20px;">No inventory found.</td>
            </tr>
            @endforelse

            <tr class="summary-row">
                <td colspan="7">TOTAL INVENTORY VALUE (COST BASIS)</td>
                <td class="text-right">{{ number_format($runningTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        ⚠ = Low stock (less than 10 bags). This is a computer-generated document. No signature is required.
    </div>
</body>
</html>
