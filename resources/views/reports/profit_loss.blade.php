<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profit &amp; Loss Report</h1>
                <p class="text-sm text-gray-500 mt-0.5">Monthly financial performance</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full space-y-6">

        <!-- Filter Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Start Date</label>
                    <input id="start_date" name="start_date" type="date" value="{{ $startDate }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">End Date</label>
                    <input id="end_date" name="end_date" type="date" value="{{ $endDate }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                    Generate Report
                </button>
            </form>
        </div>

        <!-- P&L Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-green-500 border border-gray-100 p-8">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Gross Profit (Sales)</p>
                <p class="text-3xl font-black text-green-600 font-mono mb-2">{{ number_format($totalSaleProfit, 2) }}</p>
                <p class="text-xs text-gray-500 leading-relaxed italic">Calculated as (Selling Price − Cost Price) per Bundle sold in the selected period.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-red-500 border border-gray-100 p-8">
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Expenses</p>
                <p class="text-3xl font-black text-red-600 font-mono mb-2">{{ number_format($totalExpenses, 2) }}</p>
                <p class="text-xs text-gray-500 leading-relaxed italic">All operational expenses recorded in the selected period.</p>
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
    </div>
</x-app-layout>
