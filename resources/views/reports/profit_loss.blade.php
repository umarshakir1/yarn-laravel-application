<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profit &amp; Loss Report</h1>
                <p class="text-sm text-gray-500 mt-0.5">Financial performance with per-lot &amp; per-service breakdown</p>
            </div>
            <a href="{{ route('reports.profit_loss.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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
                <a href="{{ route('reports.profit_loss') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </form>
        </div>

        <!-- P&L Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-green-500 border border-gray-100 p-8">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Gross Profit (Overall)</p>
                <p class="text-3xl font-black text-green-600 font-mono mb-2">{{ number_format($totalSaleProfit, 2) }}</p>
                <div class="flex flex-col gap-1 pt-3 border-t border-gray-100 mt-2">
                    <div class="flex justify-between text-[11px] font-bold">
                        <span class="text-gray-400">PRODUCT PROFIT:</span>
                        <span class="text-green-600">{{ number_format($totalSaleProfit - $serviceProfit, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[11px] font-bold">
                        <span class="text-indigo-400">SERVICE PROFIT:</span>
                        <span class="text-indigo-600">+ {{ number_format($serviceProfit, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-red-500 border border-gray-100 p-8">
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Expenses</p>
                <p class="text-3xl font-black text-red-600 font-mono mb-2">{{ number_format($totalExpenses, 2) }}</p>
                <p class="text-xs text-gray-500 leading-relaxed italic">All operational expenses in the selected period.</p>
            </div>

            <div class="rounded-xl shadow-md border-2 p-8 {{ $netProfit >= 0 ? 'bg-indigo-50 border-indigo-500' : 'bg-red-50 border-red-500' }}">
                <div class="w-10 h-10 {{ $netProfit >= 0 ? 'bg-indigo-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 {{ $netProfit >= 0 ? 'text-indigo-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Net Profit / Loss</p>
                <p class="text-4xl font-black font-mono mb-4 {{ $netProfit >= 0 ? 'text-indigo-700' : 'text-red-700' }}">{{ number_format($netProfit, 2) }}</p>
                @if($netProfit >= 0)
                    <span class="px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">✓ Profitable</span>
                @else
                    <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">⚠ Net Loss</span>
                @endif
            </div>
        </div>

        <!-- Per-Lot/Product Profit Breakdown -->
        @if($lotBreakdown->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Product / Lot Profit Breakdown</h3>
                <span class="ml-auto text-xs text-gray-400">{{ $startDate }} → {{ $endDate }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lot / Product</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Bundles Sold</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Cost Price / Bundle</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Avg. Sale Price / Bundle</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Revenue</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Cost</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-green-600 uppercase tracking-wider">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($lotBreakdown as $row)
                        <tr class="hover:bg-green-50/20 transition-colors even:bg-gray-50/30">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-indigo-700">{{ $row->lot_number }}</div>
                                <div class="text-xs text-gray-500">{{ $row->product_name }} <span class="text-gray-400">({{ $row->product_quality }})</span></div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-mono text-gray-700">{{ number_format($row->total_bundles, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-mono text-red-600">{{ number_format($row->cost_price, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-mono text-blue-600">{{ number_format($row->avg_sale_price, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-bold font-mono text-gray-900">{{ number_format($row->total_revenue, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-mono text-red-500">{{ number_format($row->total_cost, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-black font-mono {{ $row->total_profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($row->total_profit, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-900 text-white">
                            <td colspan="4" class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Totals</td>
                            <td class="px-6 py-4 text-right text-sm font-black font-mono text-indigo-300">{{ number_format($lotBreakdown->sum('total_revenue'), 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-black font-mono text-red-300">{{ number_format($lotBreakdown->sum('total_cost'), 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-black font-mono text-green-300">{{ number_format($lotBreakdown->sum('total_profit'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        <!-- Per-Service Profit Breakdown -->
        @if($serviceBreakdown->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-indigo-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Service Profit Breakdown</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-indigo-50 border-b border-indigo-100">
                            <th class="px-6 py-3 text-left text-xs font-bold text-indigo-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-indigo-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-indigo-500 uppercase tracking-wider">Cost</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-indigo-500 uppercase tracking-wider">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-50">
                        @foreach($serviceBreakdown as $row)
                        <tr class="hover:bg-indigo-50/40 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $row->service_name }}</td>
                            <td class="px-6 py-4 text-right text-sm font-mono text-gray-700">{{ number_format($row->total_revenue, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-mono text-red-500">{{ number_format($row->total_cost, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-black font-mono {{ $row->total_profit >= 0 ? 'text-indigo-700' : 'text-red-600' }}">
                                {{ number_format($row->total_profit, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-indigo-700 text-white">
                            <td class="px-6 py-3 text-sm font-bold uppercase tracking-wider">Totals</td>
                            <td class="px-6 py-3 text-right text-sm font-black font-mono text-indigo-200">{{ number_format($serviceBreakdown->sum('total_revenue'), 2) }}</td>
                            <td class="px-6 py-3 text-right text-sm font-black font-mono text-red-300">{{ number_format($serviceBreakdown->sum('total_cost'), 2) }}</td>
                            <td class="px-6 py-3 text-right text-sm font-black font-mono text-blue-200">{{ number_format($serviceBreakdown->sum('total_profit'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
