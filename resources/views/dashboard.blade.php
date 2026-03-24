<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-0.5">Trading ERP — Financial Summary</p>
            </div>
            <div class="text-sm text-gray-400 font-medium">{{ now()->format('l, d M Y') }}</div>
        </div>
    </x-slot>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-8 xl:px-16 py-12">

        {{-- ── Summary Stat Cards ────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">

            {{-- Receivables --}}
            <div class="min-w-0 bg-white rounded-xl shadow-sm border-t border-r border-b border-gray-100 border-l-4 border-l-blue-500 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full shrink-0">Receivable</span>
                </div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Receivables</p>
                <p class="text-2xl font-black text-gray-900 mb-4 font-mono truncate">{{ number_format($stats['total_receivable'], 2) }}</p>
                <a href="{{ route('ledgers.customers.index') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Customer Ledgers
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            {{-- Payables --}}
            <div class="min-w-0 bg-white rounded-xl shadow-sm border-t border-r border-b border-gray-100 border-l-4 border-l-red-500 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full shrink-0">Payable</span>
                </div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Payables</p>
                <p class="text-2xl font-black text-red-600 mb-4 font-mono truncate">{{ number_format($stats['total_payable'], 2) }}</p>
                <a href="{{ route('ledgers.suppliers.index') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Supplier Ledgers
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            {{-- Bank Balance --}}
            <div class="min-w-0 bg-white rounded-xl shadow-sm border-t border-r border-b border-gray-100 border-l-4 border-l-green-500 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full shrink-0">Bank</span>
                </div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Bank Balance</p>
                <p class="text-2xl font-black text-gray-900 mb-4 font-mono truncate">{{ number_format($stats['total_bank_balance'], 2) }}</p>
                <a href="{{ route('accounts.index') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition-colors">
                    Bank Accounts
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            {{-- Cash Balance --}}
            <div class="min-w-0 bg-white rounded-xl shadow-sm border-t border-r border-b border-gray-100 border-l-4 border-l-yellow-500 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-yellow-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-full shrink-0">Cash</span>
                </div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Cash Balance</p>
                <p class="text-2xl font-black text-gray-900 mb-4 font-mono truncate">{{ number_format($stats['total_cash_balance'], 2) }}</p>
                <a href="{{ route('transfers.create') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-500 text-white text-xs font-semibold rounded-lg hover:bg-yellow-600 transition-colors">
                    New Transfer
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>

        {{-- ── Bottom Two-Panel Grid ─────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch mt-12">

            {{-- Recent Sales --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                <div class="flex items-center justify-between px-6 py-4 bg-gray-800 shrink-0">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Recent Sales
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('ledgers.customers.index') }}"
                           class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                            Ledgers
                        </a>
                        <a href="{{ route('transfers.create', ['to_type' => 'customer']) }}"
                           class="px-3 py-1.5 bg-gray-600 text-white text-xs font-semibold rounded-lg hover:bg-gray-500 transition-colors">
                            + Receipt
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto grow">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice #</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($latest_sales as $sale)
                                <tr class="hover:bg-indigo-50/30 transition-colors even:bg-gray-50/40">
                                    <td class="px-5 py-3.5 text-sm font-bold text-indigo-700">{{ $sale->invoice_no }}</td>
                                    <td class="px-5 py-3.5 text-sm text-gray-600">{{ $sale->client->name }}</td>
                                    <td class="px-5 py-3.5 text-sm font-bold text-gray-900 text-right font-mono">{{ number_format($sale->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-center text-sm text-gray-400">No sales found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Stock Alerts --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                <div class="flex items-center justify-between px-6 py-4 bg-gray-800 shrink-0">
                    <h3 class="text-sm font-bold text-red-400 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Stock Alerts
                    </h3>
                    <a href="{{ route('reports.inventory') }}"
                       class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                        Inventory Report
                    </a>
                </div>
                <div class="overflow-x-auto grow">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lot #</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($low_stock_lots as $lot)
                                <tr class="hover:bg-red-50/30 transition-colors even:bg-gray-50/40">
                                    <td class="px-5 py-3.5 text-sm font-bold text-red-700">{{ $lot->lot_number }}</td>
                                    <td class="px-5 py-3.5 text-sm text-gray-600">{{ $lot->product->name }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                                            {{ number_format($lot->remaining_bags, 2) }} Bags
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-center">
                                        <div class="flex flex-col items-center gap-2 text-green-600">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm font-medium">All stocks are sufficient.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
