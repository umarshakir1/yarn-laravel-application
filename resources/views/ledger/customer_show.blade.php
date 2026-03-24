<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-1 h-7 bg-blue-500 rounded-full inline-block"></span>
                    Customer Ledger: {{ $client->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-1 ml-3.5">
                    Balance:
                    <span class="font-bold {{ $client->current_balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        {{ $client->outstandingBalanceLabel() }}
                    </span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('ledgers.customers.pdf', [$client->id, 'from' => $from, 'to' => $to]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('ledgers.customers.index') }}"
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
            <form action="{{ route('ledgers.customers.show', $client) }}" method="GET" class="flex flex-wrap gap-4 items-end">
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
                <a href="{{ route('ledgers.customers.show', $client) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
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
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-red-300">Debit (Increase)</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-green-300">Credit (Decrease)</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Opening Balance -->
                        <tr class="bg-blue-50 italic">
                            <td class="px-6 py-3.5 text-sm text-gray-500">{{ $from ? date('d-m-Y', strtotime($from)) : '—' }}</td>
                            <td class="px-6 py-3.5 text-sm font-bold text-gray-700">Opening Balance</td>
                            <td class="px-6 py-3.5 text-right text-sm text-gray-400">—</td>
                            <td class="px-6 py-3.5 text-right text-sm text-gray-400">—</td>
                            <td class="px-6 py-3.5 text-right text-sm font-bold text-gray-900 font-mono">{{ number_format($openingBalance, 2) }}</td>
                        </tr>

                        @foreach ($rows as $row)
                            <tr class="hover:bg-gray-50/60 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-3.5 text-sm text-gray-500">{{ date('d-m-Y', strtotime($row['date'])) }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-800">{{ $row['description'] }}</td>
                                <td class="px-6 py-3.5 text-right text-sm font-bold font-mono {{ $row['debit'] > 0 ? 'text-red-600' : 'text-gray-300' }}">
                                    {{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '—' }}
                                </td>
                                <td class="px-6 py-3.5 text-right text-sm font-bold font-mono {{ $row['credit'] > 0 ? 'text-green-600' : 'text-gray-300' }}">
                                    {{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '—' }}
                                </td>
                                <td class="px-6 py-3.5 text-right text-sm font-bold font-mono text-gray-900">
                                    {{ number_format($row['balance'], 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <!-- Closing Balance -->
                        <tr class="bg-gray-800 text-white">
                            <td colspan="4" class="px-6 py-4 text-sm font-bold uppercase tracking-wider">Closing Balance</td>
                            <td class="px-6 py-4 text-right text-sm font-bold font-mono">{{ number_format($closingBalance, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-red-500 border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Debits</p>
                <p class="text-2xl font-black text-red-600 font-mono">{{ number_format(collect($rows)->sum('debit'), 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-green-500 border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Credits</p>
                <p class="text-2xl font-black text-green-600 font-mono">{{ number_format(collect($rows)->sum('credit'), 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-indigo-500 border border-gray-100 p-6">
                @php $net = collect($rows)->sum('debit') - collect($rows)->sum('credit'); @endphp
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Net Change</p>
                <p class="text-2xl font-black font-mono {{ $net >= 0 ? 'text-indigo-600' : 'text-orange-600' }}">
                    {{ $net >= 0 ? '+' : '-' }}{{ number_format(abs($net), 2) }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
