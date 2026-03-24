<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">New Purchase &amp; Lot Generation</h1>
                <p class="text-sm text-gray-500 mt-0.5">Record a supplier purchase and auto-generate inventory lots</p>
            </div>
            <a href="{{ route('purchases.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full" x-data="purchaseForm()">
        <form action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Header Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 pb-3 border-b border-gray-100">Purchase Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Supplier / Party <span class="text-red-500">*</span></label>
                        <input type="text" id="supplier_search" placeholder="Type to filter suppliers..." 
                               class="w-full mb-2 rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-1.5 text-xs focus:border-indigo-400 outline-none transition-all placeholder:text-gray-400">
                        <select id="client_id" name="client_id" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }} (Bal: {{ number_format($supplier->current_balance, 2) }})</option>
                            @endforeach
                        </select>
                        @error('client_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="purchase_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Purchase Date <span class="text-red-500">*</span></label>
                        <input id="purchase_date" name="purchase_date" type="date" value="{{ old('purchase_date', date('Y-m-d')) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('purchase_date')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="invoice_no" class="block text-sm font-semibold text-gray-700 mb-1.5">Invoice Number</label>
                        <input id="invoice_no" name="invoice_no" type="text" value="{{ old('invoice_no') }}" placeholder="Optional"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('invoice_no')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Purchase Items Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Purchase Items</h3>
                    <button type="button" @click="addItem()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Row
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product / Quality</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bags (25KG)</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bundles (5KG)</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Price/Bundle</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <select :name="`items[${index}][product_id]`" x-model="item.product_id"
                                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->quality }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" :name="`items[${index}][bags]`" x-model.number="item.bags"
                                               @input="calculateBundles(index)" step="0.01" required
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                    </td>
                                    <td class="px-4 py-3 bg-gray-50/50">
                                        <span class="text-sm font-bold text-gray-700 font-mono" x-text="item.bundles"></span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" :name="`items[${index}][unit_price_per_bundle]`" x-model.number="item.price" step="0.01" required
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                    </td>
                                    <td class="px-4 py-3 text-right bg-gray-50/50">
                                        <span class="text-sm font-bold text-gray-900 font-mono" x-text="calculateSubtotal(index)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary & Payment -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Notes / Remarks</label>
                        <textarea id="notes" name="notes" rows="5"
                                  placeholder="Any specific details about this purchase..."
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors"></textarea>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xl font-bold border-b border-gray-100 pb-4">
                            <span class="text-gray-600">Grand Total:</span>
                            <span class="font-black text-gray-900 font-mono" x-text="formatCurrency(grandTotal())"></span>
                        </div>
                        <div>
                            <label for="paid_amount" class="block text-sm font-semibold text-gray-700 mb-1.5">Paid Amount <span class="text-red-500">*</span></label>
                            <input id="paid_amount" name="paid_amount" type="number" step="0.01" placeholder="0.00" required
                                   x-model.number="paidAmount"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base font-bold font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            @error('paid_amount')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg border border-red-100">
                            <span class="text-sm font-semibold text-red-700">Remaining on Supplier Ledger:</span>
                            <span class="text-base font-black text-red-700 font-mono" x-text="formatCurrency(grandTotal() - paidAmount)"></span>
                        </div>
                        <button type="submit"
                                class="w-full py-3 bg-gray-900 text-white text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                            Save Purchase &amp; Create Lots
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function purchaseForm() {
            return {
                items: [{ product_id: '', bags: 0, bundles: 0, price: 0 }],
                paidAmount: 0,
                addItem() {
                    this.items.push({ product_id: '', bags: 0, bundles: 0, price: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                calculateBundles(index) {
                    this.items[index].bundles = (this.items[index].bags * 5).toFixed(2);
                },
                calculateSubtotal(index) {
                    let subtotal = this.items[index].bundles * this.items[index].price;
                    return subtotal.toFixed(2);
                },
                grandTotal() {
                    return this.items.reduce((sum, item) => sum + (item.bundles * item.price), 0);
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('en-PK', { style: 'currency', currency: 'PKR' }).format(value);
                }
            }
        }

        // Supplier Search Filter Logic
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('supplier_search');
            const selectField = document.getElementById('client_id');
            
            if (searchInput && selectField) {
                searchInput.addEventListener('input', function() {
                    const filter = this.value.toLowerCase();
                    const options = selectField.options;
                    
                    for (let i = 1; i < options.length; i++) {
                        const text = options[i].text.toLowerCase();
                        if (text.includes(filter)) {
                            options[i].style.display = '';
                        } else {
                            options[i].style.display = 'none';
                        }
                    }
                });

                // Prevent Form Submission on Enter key in search box
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</x-app-layout>
