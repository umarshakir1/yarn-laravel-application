<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New Account</h1>
                <p class="text-sm text-gray-500 mt-0.5">Configure a Bank or Cash account</p>
            </div>
            <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('accounts.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type" required onchange="toggleBankFields()"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank Account</option>
                            <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Cash Account / Safe</option>
                        </select>
                        @error('type')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Label <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="e.g. MCB Main Account"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="bank-fields">
                        <label for="bank_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                               placeholder="e.g. Allied Bank Ltd"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('bank_name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="bank-fields">
                        <label for="account_number" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Number</label>
                        <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}"
                               placeholder="0123-4567-8901"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('account_number')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <label for="opening_balance" class="block text-sm font-semibold text-gray-700 mb-1">Opening Balance <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-400 mb-2">The starting balance correctly reflected in the system.</p>
                    <input type="number" step="0.01" name="opening_balance" id="opening_balance" value="{{ old('opening_balance', 0) }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base font-bold font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                    @error('opening_balance')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Internal Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('accounts.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Create Account</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleBankFields() {
            const type = document.getElementById('type').value;
            const fields = document.querySelectorAll('.bank-fields');
            fields.forEach(f => {
                f.style.display = type === 'bank' ? 'block' : 'none';
                const input = f.querySelector('input');
                if (type === 'cash' && input) input.value = '';
            });
        }
        document.addEventListener('DOMContentLoaded', toggleBankFields);
    </script>
    @endpush
</x-app-layout>
