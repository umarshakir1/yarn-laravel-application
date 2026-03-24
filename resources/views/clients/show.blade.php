<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5 capitalize">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $client->type == 'customer' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ $client->type }}
                    </span>
                    &bull;
                    <span class="{{ $client->is_active ? 'text-green-600' : 'text-red-500' }} font-semibold">
                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('clients.edit', $client) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('clients.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 pb-3 border-b border-gray-100">Contact Information</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Full Name</dt>
                        <dd class="text-base font-bold text-gray-900 mt-0.5">{{ $client->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Phone</dt>
                        <dd class="text-base font-medium text-gray-800 mt-0.5">{{ $client->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Address</dt>
                        <dd class="text-base font-medium text-gray-800 mt-0.5 whitespace-pre-wrap">{{ $client->address ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Financial Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 pb-3 border-b border-gray-100">Financial Status</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Opening Balance</dt>
                        <dd class="text-base font-bold text-gray-700 mt-0.5 font-mono">{{ number_format($client->opening_balance, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Current Balance</dt>
                        <dd class="text-3xl font-black mt-0.5 font-mono {{ $client->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($client->current_balance, 2) }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-6 pt-5 border-t border-gray-100">
                    @if($client->type == 'customer')
                        <a href="{{ route('ledgers.customers.show', $client) }}"
                           class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            View Customer Ledger
                        </a>
                    @else
                        <a href="{{ route('ledgers.suppliers.show', $client) }}"
                           class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-semibold rounded-lg hover:bg-orange-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            View Supplier Ledger
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
