<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">New Transfer</h1>
                <p class="text-sm text-gray-500 mt-0.5">Double-entry financial movement</p>
            </div>
            <a href="{{ route('transfers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Discard
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('transfers.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Source & Destination -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Source (Credit) -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest pb-3 border-b border-gray-100">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                Source (From)
                            </span>
                        </h3>
                        <div>
                            <label for="from_account_type" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Type <span class="text-red-500">*</span></label>
                            <select name="from_account_type" id="from_account_type" required onchange="updateAccountList('from')"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                                <option value="">Select Type</option>
                                <option value="bank" {{ old('from_account_type', request('from_type')) == 'bank' ? 'selected' : '' }}>Bank Account</option>
                                <option value="cash" {{ old('from_account_type', request('from_type')) == 'cash' ? 'selected' : '' }}>Cash Account</option>
                                <option value="customer" {{ old('from_account_type', request('from_type')) == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="supplier" {{ old('from_account_type', request('from_type')) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            </select>
                        </div>
                        <div>
                            <label for="from_account_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Name <span class="text-red-500">*</span></label>
                            <select name="from_account_id" id="from_account_id" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                                <option value="">Select Account</option>
                            </select>
                        </div>
                    </div>

                    <!-- Arrow divider -->
                    <div class="hidden md:flex items-center justify-center absolute left-1/2 -translate-x-1/2 mt-10">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>

                    <!-- Destination (Debit) -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest pb-3 border-b border-gray-100">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                Destination (To)
                            </span>
                        </h3>
                        <div>
                            <label for="to_account_type" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Type <span class="text-red-500">*</span></label>
                            <select name="to_account_type" id="to_account_type" required onchange="updateAccountList('to')"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                                <option value="">Select Type</option>
                                <option value="bank" {{ old('to_account_type', request('to_type')) == 'bank' ? 'selected' : '' }}>Bank Account</option>
                                <option value="cash" {{ old('to_account_type', request('to_type')) == 'cash' ? 'selected' : '' }}>Cash Account</option>
                                <option value="customer" {{ old('to_account_type', request('to_type')) == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="supplier" {{ old('to_account_type', request('to_type')) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            </select>
                        </div>
                        <div>
                            <label for="to_account_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Account Name <span class="text-red-500">*</span></label>
                            <select name="to_account_id" id="to_account_id" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                                <option value="">Select Account</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Details Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                    <div>
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-1.5">Transaction Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1.5">Amount <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', request('amount')) }}" required
                               placeholder="0.00"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-bold font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                    </div>
                    <div>
                        <label for="reference_no" class="block text-sm font-semibold text-gray-700 mb-1.5">Reference #</label>
                        <input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no') }}"
                               placeholder="Optional"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm uppercase focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description / Notes</label>
                    <textarea name="description" id="description" rows="2"
                              placeholder="Describe the purpose of this transfer..."
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 italic font-medium uppercase tracking-wider">Balanced Double-Entry Mode</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('transfers.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">Discard</a>
                        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">Post Transaction</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const accounts = {
            bank: {!! $accounts->where('type', 'bank')->toJson() !!},
            cash: {!! $accounts->where('type', 'cash')->toJson() !!},
            customer: {!! $customers->toJson() !!},
            supplier: {!! $suppliers->toJson() !!}
        };

        const oldFromId = "{{ old('from_account_id', request('from_id')) }}";
        const oldToId = "{{ old('to_account_id', request('to_id')) }}";

        function updateAccountList(direction) {
            const type = document.getElementById(direction + '_account_type').value;
            const select = document.getElementById(direction + '_account_id');
            const oldId = direction === 'from' ? oldFromId : oldToId;

            select.innerHTML = '<option value="">Select Account</option>';

            if (type && accounts[type]) {
                accounts[type].forEach(acc => {
                    const option = document.createElement('option');
                    option.value = acc.id;
                    option.textContent = acc.name + (acc.account_number ? ` (${acc.account_number})` : '');
                    if (acc.id == oldId) option.selected = true;
                    select.appendChild(option);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateAccountList('from');
            updateAccountList('to');
        });
    </script>
    @endpush
</x-app-layout>
