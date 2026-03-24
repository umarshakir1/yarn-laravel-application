<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bank &amp; Cash Accounts</h1>
                <p class="text-sm text-gray-500 mt-0.5">Internal financial accounts overview</p>
            </div>
            <a href="{{ route('accounts.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Account
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($accounts as $account)
                @php $isBank = $account->type == 'bank'; @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Card Header -->
                    <div class="px-6 py-5 {{ $isBank ? 'bg-gradient-to-r from-blue-600 to-blue-700' : 'bg-gradient-to-r from-teal-600 to-teal-700' }} text-white">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                @if($isBank)
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $isBank ? 'bg-blue-500/40' : 'bg-teal-500/40' }} uppercase tracking-wider">
                                    {{ $account->getTypeLabel() }}
                                </span>
                                @if(!$account->is_active)
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-red-500/40 uppercase">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-white">{{ $account->name }}</h3>
                        @if($isBank && $account->bank_name)
                            <p class="text-xs text-white/60 mt-0.5">{{ $account->bank_name }}</p>
                        @endif
                    </div>

                    <!-- Balance -->
                    <div class="px-6 py-5 border-b border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Current Balance</p>
                        <p class="text-3xl font-black text-gray-900 font-mono">{{ number_format($account->current_balance, 2) }}</p>
                        @if($isBank && $account->account_number)
                            <p class="text-xs text-gray-400 mt-2">A/C: {{ $account->account_number }}</p>
                        @else
                            <p class="text-xs text-gray-400 mt-2 italic">Physical cash account</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 flex flex-col gap-2">
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('transfers.create', ['from_type' => $account->type, 'from_id' => $account->id]) }}"
                               class="inline-flex justify-center items-center gap-1.5 px-3 py-2 bg-gray-800 text-white text-xs font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                Send Money
                            </a>
                            <a href="{{ route('transfers.create', ['to_type' => $account->type, 'to_id' => $account->id]) }}"
                               class="{{ $isBank ? 'bg-blue-600 hover:bg-blue-700' : 'bg-teal-600 hover:bg-teal-700' }} inline-flex justify-center items-center gap-1.5 px-3 py-2 text-white text-xs font-semibold rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                Receive Money
                            </a>
                        </div>
                        <a href="{{ route('accounts.edit', $account) }}"
                           class="inline-flex justify-center items-center gap-1.5 px-3 py-2 bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Account Settings
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <p class="text-gray-400 font-medium mb-4">No accounts configured yet.</p>
                    <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                        Create Your First Account
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
