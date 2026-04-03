<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Sales Report</h1>
                <p class="text-sm text-gray-500 mt-0.5">Detailed sales performance overview</p>
            </div>
            <a href="{{ route('reports.sales.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Export PDF
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full space-y-6">

        <!-- Filter Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">From Date</label>
                    <input id="start_date" name="start_date" type="date" value="{{ $startDate }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">To Date</label>
                    <input id="end_date" name="end_date" type="date" value="{{ $endDate }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                    Filter
                </button>
                <a href="{{ route('reports.sales') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-blue-500 border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Sales (Inc. Services)</p>
                <p class="text-3xl font-black text-blue-600 font-mono">{{ number_format($summary['total_sales'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-green-500 border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Paid</p>
                <p class="text-3xl font-black text-green-600 font-mono">{{ number_format($summary['total_paid'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-indigo-500 border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Overall Total Profit</p>
                <p class="text-3xl font-black text-indigo-600 font-mono">{{ number_format($summary['total_profit'], 2) }}</p>
            </div>
        </div>

        <!-- Service Performance Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-indigo-400 border border-gray-100 p-6 scale-95 opacity-90 transition-all hover:scale-100 hover:opacity-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Service Revenue</p>
                <p class="text-2xl font-black text-indigo-700 font-mono">{{ number_format($summary['service_revenue'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-red-400 border border-gray-100 p-6 scale-95 opacity-90 transition-all hover:scale-100 hover:opacity-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Service Cost</p>
                <p class="text-2xl font-black text-red-600 font-mono">{{ number_format($summary['service_cost'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-green-400 border border-gray-100 p-6 scale-95 opacity-90 transition-all hover:scale-100 hover:opacity-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-green-600">Service Profit</p>
                <p class="text-2xl font-black text-green-700 font-mono">{{ number_format($summary['service_profit'], 2) }}</p>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-green-300">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-indigo-50/30 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-indigo-700">{{ $sale->invoice_no }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $sale->client->name }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 font-mono">{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-green-600 font-mono">{{ number_format($sale->total_profit, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-400">No sales found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($sales->count() > 0)
                    <tfoot>
                        <tr class="bg-gray-900 text-white">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Totals</td>
                            <td class="px-6 py-4 text-right text-sm font-black font-mono text-indigo-300">{{ number_format($summary['total_sales'], 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-black font-mono text-green-300">{{ number_format($summary['total_profit'], 2) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
