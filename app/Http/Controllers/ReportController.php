<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Lot;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $sales = Sale::with(['client', 'services'])
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->latest()
            ->get();

        // Calculate Service Summary
        $serviceMetrics = DB::table('sale_services')
            ->join('sales', 'sales.id', '=', 'sale_services.sale_id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(price) as revenue'),
                DB::raw('SUM(service_cost) as cost'),
                DB::raw('SUM(service_profit) as profit')
            )->first();

        $summary = [
            'total_sales' => $sales->sum('total_amount'),
            'total_paid' => $sales->sum('paid_amount'),
            'total_profit' => $sales->sum('total_profit'),
            'service_revenue' => $serviceMetrics->revenue ?? 0,
            'service_cost' => $serviceMetrics->cost ?? 0,
            'service_profit' => $serviceMetrics->profit ?? 0,
        ];

        return view('reports.sales', compact('sales', 'summary', 'startDate', 'endDate'));
    }

    public function inventory()
    {
        $lots = Lot::with('product')->where('remaining_bags', '>', 0)->get();
        return view('reports.inventory', compact('lots'));
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $totalSaleProfit = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_profit');
        
        // Separate Service Profit for the P&L page
        $serviceProfit = DB::table('sale_services')
            ->join('sales', 'sales.id', '=', 'sale_services.sale_id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->sum('service_profit');

        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $netProfit = $totalSaleProfit - $totalExpenses;

        return view('reports.profit_loss', compact('startDate', 'endDate', 'totalSaleProfit', 'serviceProfit', 'totalExpenses', 'netProfit'));
    }
}
