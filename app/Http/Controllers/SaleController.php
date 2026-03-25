<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Lot;
use App\Models\Service;
use App\Http\Requests\SaleRequest;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index()
    {
        $sales = Sale::with('client')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $clients  = Client::whereIn('type', ['customer', 'both'])->active()->get();
        $lots     = Lot::with('product')->available()->get();
        $services = Service::active()->orderBy('name')->get();
        return view('sales.create', compact('clients', 'lots', 'services'));
    }

    public function store(SaleRequest $request)
    {
        try {
            $this->saleService->createSale($request->validated());
            return redirect()->route('sales.index')->with('success', 'Sale recorded successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['client', 'items.product', 'items.lot', 'services']);
        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        $this->saleService->deleteSale($sale);
        return redirect()->route('sales.index')->with('success', 'Sale deleted and inventory restored.');
    }
}
