<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\Client;
use App\Models\Product;
use App\Http\Requests\PurchaseRequest;
use App\Services\PurchaseService;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with('client')->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Client::whereIn('type', ['supplier', 'both'])->active()->get();
        $products = Product::active()->get();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {
        $this->purchaseService->createPurchase($request->validated());
        return redirect()->route('purchases.index')->with('success', 'Purchase recorded and lots created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['client', 'items.product', 'items.lot']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $this->purchaseService->deletePurchase($purchase);
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted and balances adjusted.');
    }
}
