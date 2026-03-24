<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Sales Invoices</h1>
                <p class="text-sm text-gray-500 mt-0.5">All sales transactions</p>
            </div>
            <a href="{{ route('sales.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors duration-150 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Sale
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Profit</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-indigo-50/30 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-indigo-700">{{ $sale->invoice_no }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $sale->client->name }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 font-mono">{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-green-600 font-mono">{{ number_format($sale->paid_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold font-mono text-blue-600">{{ number_format($sale->total_profit, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('sales.show', $sale) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg hover:bg-indigo-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('This will restore inventory. Continue?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-200 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <p class="text-gray-400 font-medium">No sales found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $sales->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
