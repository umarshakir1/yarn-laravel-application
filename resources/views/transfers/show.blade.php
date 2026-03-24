<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Transfer: {{ $transfer->reference_no }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ date('l, d M Y', strtotime($transfer->date)) }}</p>
            </div>
            <a href="{{ route('transfers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to History
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Transaction Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-800">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Transaction Summary</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                            <p class="text-sm font-bold text-gray-900">{{ date('d-M-Y', strtotime($transfer->date)) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Amount</p>
                            <p class="text-lg font-black text-indigo-600 font-mono">{{ number_format($transfer->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Direction</p>
                            <p class="text-sm font-bold text-gray-900">{{ $transfer->getDirectionLabel() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Logged At</p>
                            <p class="text-sm font-bold text-gray-900">{{ $transfer->created_at->format('d-M-Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Movement -->
                    <div class="px-6 pb-6">
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1 text-center">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">From</p>
                                <p class="text-sm font-bold text-gray-900">{{ $transfer->from_label }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            </div>
                            <div class="flex-1 text-center">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">To</p>
                                <p class="text-sm font-bold text-indigo-700">{{ $transfer->to_label }}</p>
                            </div>
                        </div>

                        @if($transfer->description)
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg italic text-gray-600 text-sm">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1 not-italic">Notes</p>
                                {{ $transfer->description }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ledger Entries -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-800">
                        <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider">Generated Ledger Entries (Double-Entry)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Account</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Entry Type</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($transfer->ledgerEntries as $entry)
                                    <tr class="hover:bg-indigo-50/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900">{{ ucfirst($entry->account_type) }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $transfer->{$entry->entry_type == 'debit' ? 'to_label' : 'from_label'} }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($entry->entry_type == 'debit')
                                                <span class="inline-flex items-center px-2.5 py-1 bg-indigo-100 text-indigo-800 text-xs font-bold rounded-full uppercase">Debit (+)</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full uppercase">Credit (−)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold font-mono {{ $entry->entry_type == 'debit' ? 'text-indigo-600' : 'text-gray-600' }}">
                                            {{ number_format($entry->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-800">
                        <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">Internal Controls</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <p class="text-sm text-gray-500 leading-relaxed">
                            This transaction follows the double-entry principal. Reversing it will permanently restore balances for both parties.
                        </p>
                        <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                            <p class="text-xs text-red-700 font-bold uppercase tracking-wider">⚠ Warning: This action cannot be undone.</p>
                        </div>
                        <form action="{{ route('transfers.destroy', $transfer) }}" method="POST"
                              onsubmit="return confirm('CRITICAL: Reverse this transaction? This will permanently restore balances and delete the audit trail rows.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-3 bg-red-700 hover:bg-red-800 text-white text-sm font-bold rounded-lg uppercase tracking-wider transition-colors shadow-sm">
                                Reverse Entry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
