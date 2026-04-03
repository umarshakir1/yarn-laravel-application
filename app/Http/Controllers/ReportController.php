<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Purchase;
use App\Models\Lot;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // ─── Sales Report ────────────────────────────────────────────────────────

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate   = $request->get('end_date', date('Y-m-d'));

        $sales = Sale::with(['client', 'services'])
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $serviceMetrics = DB::table('sale_services')
            ->join('sales', 'sales.id', '=', 'sale_services.sale_id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(price) as revenue'),
                DB::raw('SUM(service_cost) as cost'),
                DB::raw('SUM(service_profit) as profit')
            )->first();

        $summary = [
            'total_sales'     => $sales->sum('total_amount'),
            'total_paid'      => $sales->sum('paid_amount'),
            'total_profit'    => $sales->sum('total_profit'),
            'service_revenue' => $serviceMetrics->revenue ?? 0,
            'service_cost'    => $serviceMetrics->cost    ?? 0,
            'service_profit'  => $serviceMetrics->profit  ?? 0,
        ];

        return view('reports.sales', compact('sales', 'summary', 'startDate', 'endDate'));
    }

    public function salesPdf(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate   = $request->get('end_date', date('Y-m-d'));

        $sales = Sale::with(['client', 'services'])
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $serviceMetrics = DB::table('sale_services')
            ->join('sales', 'sales.id', '=', 'sale_services.sale_id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(price) as revenue'),
                DB::raw('SUM(service_cost) as cost'),
                DB::raw('SUM(service_profit) as profit')
            )->first();

        $summary = [
            'total_sales'     => $sales->sum('total_amount'),
            'total_paid'      => $sales->sum('paid_amount'),
            'total_profit'    => $sales->sum('total_profit'),
            'service_revenue' => $serviceMetrics->revenue ?? 0,
            'service_cost'    => $serviceMetrics->cost    ?? 0,
            'service_profit'  => $serviceMetrics->profit  ?? 0,
        ];

        $pdf = Pdf::loadView('reports.pdf.sales', compact('sales', 'summary', 'startDate', 'endDate'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("sales-report-{$startDate}-to-{$endDate}.pdf");
    }

    // ─── Inventory Report ─────────────────────────────────────────────────────

    public function inventory(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $query = Lot::with('product')->where('remaining_bags', '>', 0);

        // Filter by purchase date if provided
        if ($startDate) {
            $query->whereHas('purchaseItem.purchase', fn($q) => $q->where('purchase_date', '>=', $startDate));
        }
        if ($endDate) {
            $query->whereHas('purchaseItem.purchase', fn($q) => $q->where('purchase_date', '<=', $endDate));
        }

        $lots = $query->get();

        return view('reports.inventory', compact('lots', 'startDate', 'endDate'));
    }

    public function inventoryPdf(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $query = Lot::with('product')->where('remaining_bags', '>', 0);

        if ($startDate) {
            $query->whereHas('purchaseItem.purchase', fn($q) => $q->where('purchase_date', '>=', $startDate));
        }
        if ($endDate) {
            $query->whereHas('purchaseItem.purchase', fn($q) => $q->where('purchase_date', '<=', $endDate));
        }

        $lots = $query->get();

        $pdf = Pdf::loadView('reports.pdf.inventory', compact('lots', 'startDate', 'endDate'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("inventory-report-" . now()->format('Ymd') . ".pdf");
    }

    // ─── Profit & Loss Report ─────────────────────────────────────────────────

    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate   = $request->get('end_date', date('Y-m-d'));

        $data = $this->buildProfitLossData($startDate, $endDate);

        return view('reports.profit_loss', array_merge($data, [
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]));
    }

    public function profitLossPdf(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate   = $request->get('end_date', date('Y-m-d'));

        $data = $this->buildProfitLossData($startDate, $endDate);

        $pdf = Pdf::loadView('reports.pdf.profit_loss', array_merge($data, [
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]))->setPaper('a4', 'landscape');

        return $pdf->download("profit-loss-{$startDate}-to-{$endDate}.pdf");
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function buildProfitLossData(string $startDate, string $endDate): array
    {
        $totalSaleProfit = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_profit');

        $serviceProfit = DB::table('sale_services')
            ->join('sales', 'sales.id', '=', 'sale_services.sale_id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->sum('service_profit');

        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $netProfit     = $totalSaleProfit - $totalExpenses;

        // Per-lot breakdown: aggregate sale_items joined with lots and products
        $lotBreakdown = SaleItem::query()
            ->join('sales',    'sales.id',    '=', 'sale_items.sale_id')
            ->join('lots',     'lots.id',     '=', 'sale_items.lot_id')
            ->join('products', 'products.id', '=', 'lots.product_id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                'lots.id as lot_id',
                'lots.lot_number',
                'products.name as product_name',
                'products.quality as product_quality',
                DB::raw('SUM(sale_items.bundles) as total_bundles'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue'),
                DB::raw('SUM(sale_items.profit) as total_profit'),
                // cost = revenue - profit
                DB::raw('SUM(sale_items.subtotal) - SUM(sale_items.profit) as total_cost'),
                DB::raw('AVG(sale_items.unit_price_per_bundle) as avg_sale_price'),
                DB::raw('MIN(lots.cost_price_per_bundle) as cost_price')
            )
            ->groupBy('lots.id', 'lots.lot_number', 'products.name', 'products.quality')
            ->orderByDesc('total_profit')
            ->get();

        // Per-service breakdown
        $serviceBreakdown = DB::table('sale_services')
            ->join('sales',    'sales.id',    '=', 'sale_services.sale_id')
            ->join('services', 'services.id', '=', 'sale_services.service_id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                'services.name as service_name',
                DB::raw('SUM(sale_services.price) as total_revenue'),
                DB::raw('SUM(sale_services.service_cost) as total_cost'),
                DB::raw('SUM(sale_services.service_profit) as total_profit')
            )
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total_profit')
            ->get();

        return compact(
            'totalSaleProfit', 'serviceProfit', 'totalExpenses', 'netProfit',
            'lotBreakdown', 'serviceBreakdown'
        );
    }
}
