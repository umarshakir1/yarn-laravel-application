<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-1 h-7 bg-orange-500 rounded-full inline-block"></span>
                    Supplier Ledger: {{ $client->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-1 ml-3.5">
                    Outstanding:
                    <span class="font-bold {{ $client->current_balance <= 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $client->outstandingBalanceLabel() }}
                    </span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('transfers.create', ['to_type' => 'supplier', 'to_id' => $client->id, 'amount' => abs($client->current_balance)]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-800 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Record Payment
                </a>
                <a href="{{ route('ledgers.suppliers.pdf', [$client->id, 'from' => $from, 'to' => $to]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('ledgers.suppliers.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full space-y-6">

        <!-- Date Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('ledgers.suppliers.show', $client) }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="from" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">From Date</label>
                    <input type="date" name="from" id="from" value="{{ $from }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <div>
                    <label for="to" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">To Date</label>
                    <input type="date" name="to" id="to" value="{{ $to }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                    Filter History
                </button>
                <a href="{{ route('ledgers.suppliers.show', $client) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </form>
        </div>

        <!-- Ledger Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-red-300">Credit (Purchases)</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-green-300">Debit (Payments)</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Opening Balance -->
                        <tr class="bg-orange-50 italic">
                            <td class="px-6 py-3.5 text-sm text-gray-500">{{ $from ? date('d-m-Y', strtotime($from)) : '—' }}</td>
                            <td class="px-6 py-3.5 text-sm font-bold text-gray-700 uppercase">Opening Balance</td>
                            <td class="px-6 py-3.5 text-right text-sm text-gray-400">—</td>
                            <td class="px-6 py-3.5 text-right text-sm text-gray-400">—</td>
                            <td class="px-6 py-3.5 text-right text-sm font-bold text-gray-900 font-mono">{{ number_format(abs($openingBalance), 2) }}</td>
                        </tr>

                        @foreach ($rows as $row)
                            <tr class="hover:bg-gray-50/60 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-3.5 text-sm text-gray-500">{{ date('d-m-Y', strtotime($row['date'])) }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-800">{{ $row['description'] }}</td>
                                <td class="px-6 py-3.5 text-right text-sm font-bold font-mono {{ $row['credit'] > 0 ? 'text-red-600' : 'text-gray-300' }}">
                                    {{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '—' }}
                                </td>
                                <td class="px-6 py-3.5 text-right text-sm font-bold font-mono {{ $row['debit'] > 0 ? 'text-green-600' : 'text-gray-300' }}">
                                    {{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '—' }}
                                </td>
                                <td class="px-6 py-3.5 text-right text-sm font-bold font-mono text-gray-900">
                                    {{ number_format(abs($row['balance']), 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <!-- Closing Balance -->
                        <tr class="bg-orange-600 text-white">
                            <td colspan="4" class="px-6 py-4 text-sm font-bold uppercase tracking-wider">Closing Balance (Payable)</td>
                            <td class="px-6 py-4 text-right text-sm font-bold font-mono">{{ number_format(abs($closingBalance), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-red-500 border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Purchases (Credit)</p>
                <p class="text-2xl font-black text-red-600 font-mono">{{ number_format(collect($rows)->sum('credit'), 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-green-500 border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Payments Made (Debit)</p>
                <p class="text-2xl font-black text-green-600 font-mono">{{ number_format(collect($rows)->sum('debit'), 2) }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
