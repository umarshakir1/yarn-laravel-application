<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Live Inventory Report</h1>
                <p class="text-sm text-gray-500 mt-0.5">Current stock levels by lot</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Lot Number</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Initial Bags</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Remaining Bags</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Rem. Bundles</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Cost/Bundle</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-green-300">Inventory Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $totalValue = 0; @endphp
                        @foreach($lots as $lot)
                            @php
                                $v = $lot->remaining_bags * 5 * $lot->cost_price_per_bundle;
                                $totalValue += $v;
                                $isLow = $lot->remaining_bags < 10;
                            @endphp
                            <tr class="hover:bg-indigo-50/20 transition-colors even:bg-gray-50/40 {{ $isLow ? 'bg-red-50/40' : '' }}">
                                <td class="px-6 py-4 text-sm font-bold text-indigo-700">{{ $lot->lot_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $lot->product->name }}
                                    <span class="text-xs text-gray-400">({{ $lot->product->quality }})</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-600 font-mono">{{ number_format($lot->initial_bags, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($isLow)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ number_format($lot->remaining_bags, 2) }}
                                        </span>
                                    @else
                                        <span class="text-sm font-mono text-gray-700">{{ number_format($lot->remaining_bags, 2) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-mono text-gray-600">{{ number_format($lot->remaining_bags * 5, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-mono text-gray-600">{{ number_format($lot->cost_price_per_bundle, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold font-mono text-green-700">{{ number_format($v, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-900 text-white">
                            <td colspan="6" class="px-6 py-4 text-sm font-bold uppercase tracking-wider text-right">Total Inventory Value (Cost)</td>
                            <td class="px-6 py-4 text-right text-lg font-black font-mono text-green-300">{{ number_format($totalValue, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
