<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Account: {{ $account->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $account->getTypeLabel() }}</p>
            </div>
            <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')
                <input type="hidden" name="type" value="{{ $account->type }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Account Type</label>
                        <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 font-bold">{{ $account->getTypeLabel() }}</div>
                        <p class="text-xs text-gray-400 mt-1">Type cannot be changed after creation.</p>
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Label <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    @if($account->type == 'bank')
                        <div>
                            <label for="bank_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $account->bank_name) }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            @error('bank_name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="account_number" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Number</label>
                            <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $account->account_number) }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            @error('account_number')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    @endif

                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Status</label>
                        <select name="is_active" id="is_active"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            <option value="1" {{ old('is_active', $account->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $account->is_active) ? 'selected' : '' }}>Inactive / Closed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Current Ledger Balance</label>
                        <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 font-mono font-bold">{{ number_format($account->current_balance, 2) }}</div>
                        <p class="text-xs text-gray-400 mt-1">Balance changes only via transfers or adjustments.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Internal Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">{{ old('notes', $account->notes) }}</textarea>
                        @error('notes')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline"
                          onsubmit="return confirm('Delete this account? This will only work if there are no existing transactions. Consider marking it Inactive instead.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 uppercase tracking-wider transition-colors">Delete Permanently</button>
                    </form>
                    <div class="flex gap-3">
                        <a href="{{ route('accounts.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Update Account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
