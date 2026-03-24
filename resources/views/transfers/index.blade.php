<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Transfer History</h1>
                <p class="text-sm text-gray-500 mt-0.5">Double-entry financial movements</p>
            </div>
            <a href="{{ route('transfers.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Transfer
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full space-y-6">

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('transfers.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">From Date</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">To Date</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Account Type</label>
                    <select name="account_type" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        <option value="">All Types</option>
                        <option value="bank" {{ request('account_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="cash" {{ request('account_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="customer" {{ request('account_type') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="supplier" {{ request('account_type') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">Filter</button>
                    <a href="{{ route('transfers.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Ref / Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Movement</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transfers as $transfer)
                            <tr class="hover:bg-indigo-50/20 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $transfer->reference_no }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ date('d M Y', strtotime($transfer->date)) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="font-medium text-gray-800">{{ $transfer->from_label }}</span>
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        <span class="font-bold text-indigo-600">{{ $transfer->to_label }}</span>
                                    </div>
                                    @if($transfer->description)
                                        <div class="text-xs text-gray-400 mt-1 italic truncate max-w-xs">{{ $transfer->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 font-mono">
                                    {{ number_format($transfer->amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('transfers.show', $transfer) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg hover:bg-indigo-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Audit
                                        </a>
                                        <form action="{{ route('transfers.destroy', $transfer) }}" method="POST" class="inline" onsubmit="return confirm('Reverse this transfer? This will undo all balance changes.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-200 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Reverse
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <p class="text-gray-400 font-medium">No transfers recorded yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transfers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $transfers->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
