<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Purchase: {{ $purchase->invoice_no ?? '#' . $purchase->id }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('l, d M Y') }} &bull; {{ $purchase->client->name }}</p>
            </div>
            <a href="{{ route('purchases.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full space-y-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-blue-500 border border-gray-100 p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Supplier</p>
                <p class="text-base font-bold text-gray-900">{{ $purchase->client->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $purchase->client->phone }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-gray-400 border border-gray-100 p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                <p class="text-base font-bold text-gray-900">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-green-500 border border-gray-100 p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Amount</p>
                <p class="text-xl font-black text-gray-900 font-mono">{{ number_format($purchase->total_amount, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-l-indigo-500 border border-gray-100 p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Amount Paid</p>
                <p class="text-xl font-black text-indigo-700 font-mono">{{ number_format($purchase->paid_amount, 2) }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Product Items &amp; Lot Allocation</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Bags</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Bundles</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Cost/Bundle</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lot #</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($purchase->items as $item)
                            <tr class="hover:bg-indigo-50/20 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $item->product->name }}
                                    <span class="text-gray-400">({{ $item->product->quality }})</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-600 font-mono">{{ $item->bags }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-600 font-mono">{{ $item->bundles }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-600 font-mono">{{ number_format($item->unit_price_per_bundle, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                        {{ $item->lot->lot_number ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 font-mono">{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-800 text-white">
                            <td colspan="5" class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Grand Total</td>
                            <td class="px-6 py-4 text-right text-base font-black font-mono text-indigo-300">{{ number_format($purchase->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($purchase->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Notes</p>
                <p class="text-gray-700 italic">{{ $purchase->notes }}</p>
            </div>
        @endif
    </div>
</x-app-layout>
