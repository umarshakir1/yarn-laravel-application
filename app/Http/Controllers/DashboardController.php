<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Lot;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');

        $stats = [
            'today_sales' => Sale::where('sale_date', $today)->sum('total_amount'),
            'today_profit' => Sale::where('sale_date', $today)->sum('total_profit'),
            'month_sales' => Sale::whereBetween('sale_date', [$monthStart, $today])->sum('total_amount'),
            'month_profit' => Sale::whereBetween('sale_date', [$monthStart, $today])->sum('total_profit'),
            'total_receivable' => Client::whereIn('type', ['customer', 'both'])->where('current_balance', '>', 0)->sum('current_balance'),
            'total_payable' => abs(Client::whereIn('type', ['supplier', 'both'])->where('current_balance', '<', 0)->sum('current_balance')),
            'inventory_value' => Lot::where('remaining_bags', '>', 0)->sum(DB::raw('remaining_bags * 5 * cost_price_per_bundle')),
            'total_bank_balance' => Account::bank()->sum('current_balance'),
            'total_cash_balance' => Account::cash()->sum('current_balance'),
        ];

        $latest_sales = Sale::with('client')->latest()->take(5)->get();
        $low_stock_lots = Lot::with('product')->where('remaining_bags', '>', 0)->where('remaining_bags', '<', 50)->get();

        return view('dashboard', compact('stats', 'latest_sales', 'low_stock_lots'));
    }
}
