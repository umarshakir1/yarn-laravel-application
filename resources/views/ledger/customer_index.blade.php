<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Customer Ledgers</h1>
                <p class="text-sm text-gray-500 mt-0.5">Outstanding balances for all customers</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Customer Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Current Balance</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-indigo-50/30 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $customer->name }}</div>
                                    @if($customer->email)
                                        <div class="text-xs text-gray-400">{{ $customer->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $customer->phone ?? '—' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold font-mono {{ $customer->current_balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                        {{ number_format(abs($customer->current_balance), 2) }}
                                    </span>
                                    <span class="ml-1 text-xs font-semibold px-1.5 py-0.5 rounded {{ $customer->current_balance >= 0 ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $customer->current_balance >= 0 ? 'Rec.' : 'Pay.' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($customer->is_active)
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
                                    <a href="{{ route('ledgers.customers.show', $customer) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 text-white text-xs font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        View Ledger
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="text-gray-400 font-medium">No customers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
