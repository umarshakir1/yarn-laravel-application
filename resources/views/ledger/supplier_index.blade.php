<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Supplier Ledgers</h1>
                <p class="text-sm text-gray-500 mt-0.5">Outstanding balances for all suppliers</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Supplier Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Current Balance</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-orange-50/20 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $supplier->name }}</div>
                                    @if($supplier->email)
                                        <div class="text-xs text-gray-400">{{ $supplier->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $supplier->phone ?? '—' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold font-mono {{ $supplier->current_balance < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format(abs($supplier->current_balance), 2) }}
                                    </span>
                                    <span class="ml-1 text-xs font-semibold px-1.5 py-0.5 rounded {{ $supplier->current_balance <= 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $supplier->current_balance <= 0 ? 'Payable' : 'Adv.' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($supplier->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('ledgers.suppliers.show', $supplier) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 text-white text-xs font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        View Ledger
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="text-gray-400 font-medium">No suppliers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
