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
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bundles</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <select :name="`items[${index}][product_id]`" x-model="item.product_id"
                                                @change="onProductChange(index)"
                                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-unit-type="{{ $product->unit_type }}">{{ $product->name }} ({{ $product->quality }})</option>
                                            @endforeach
                                        </select>
                                        <span class="mt-1 inline-block text-[10px] font-bold uppercase tracking-wider"
                                              :class="item.unit_type === 'per_kg' ? 'text-blue-500' : 'text-gray-400'"
                                              x-text="item.unit_type === 'per_kg' ? 'Per KG' : 'Per Bag'"></span>
                                    </td>

                                    <!-- Per Bag: Bags input -->
                                    <td class="px-4 py-3" x-show="item.unit_type === 'per_bag'">
                                        <input type="number" :name="`items[${index}][bags]`" x-model.number="item.bags"
                                               @input="calculateBundles(index)" step="0.01" min="0.01"
                                               :required="item.unit_type === 'per_bag'"
                                               :disabled="item.unit_type !== 'per_bag'"
                                               placeholder="Bags"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                    </td>

                                    <!-- Per KG: KG input + hidden bags + hidden kg_quantity -->
                                    <td class="px-4 py-3" x-show="item.unit_type === 'per_kg'">
                                        <input type="number" x-model.number="item.kg"
                                               @input="calculateFromKg(index)" step="0.01" min="0.01"
                                               :required="item.unit_type === 'per_kg'"
                                               placeholder="KG"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                        <input type="hidden" :name="`items[${index}][bags]`" :value="item.bags" :disabled="item.unit_type !== 'per_kg'">
                                        <input type="hidden" :name="`items[${index}][kg_quantity]`" :value="item.kg" :disabled="item.unit_type !== 'per_kg'">
                                    </td>

                                    <!-- Bundles display (per_bag only; empty cell for per_kg) -->
                                    <td class="px-4 py-3 bg-gray-50/50">
                                        <span class="text-sm font-bold text-gray-700 font-mono" x-show="item.unit_type === 'per_bag'" x-text="item.bundles"></span>
                                    </td>

                                    <!-- Per Bag: Price/Bundle input -->
                                    <td class="px-4 py-3" x-show="item.unit_type === 'per_bag'">
                                        <label class="block text-[10px] text-gray-400 font-bold uppercase mb-0.5">Price/Bundle</label>
                                        <input type="number" :name="`items[${index}][unit_price_per_bundle]`" x-model.number="item.price"
                                               step="0.01" min="0" :required="item.unit_type === 'per_bag'"
                                               :disabled="item.unit_type !== 'per_bag'"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                    </td>

                                    <!-- Per KG: Price/KG input + hidden unit_price_per_bundle -->
                                    <td class="px-4 py-3" x-show="item.unit_type === 'per_kg'">
                                        <label class="block text-[10px] text-blue-500 font-bold uppercase mb-0.5">Price/KG</label>
                                        <input type="number" x-model.number="item.price_per_kg"
                                               @input="calculatePriceFromKg(index)" step="0.01" min="0"
                                               :required="item.unit_type === 'per_kg'"
                                               class="block w-full rounded-lg border border-blue-300 px-3 py-2 text-sm font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
                                        <input type="hidden" :name="`items[${index}][unit_price_per_bundle]`" :value="item.price" :disabled="item.unit_type !== 'per_kg'">
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
        // Product unit_type map: { id: 'per_bag'|'per_kg' }
        const productUnitTypes = @json($products->pluck('unit_type', 'id'));

        function newItem() {
            return { product_id: '', unit_type: 'per_bag', bags: 0, bundles: 0, price: 0, kg: 0, price_per_kg: 0 };
        }

        function purchaseForm() {
            return {
                items: [newItem()],
                paidAmount: 0,

                addItem() {
                    this.items.push(newItem());
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                onProductChange(index) {
                    const pid = this.items[index].product_id;
                    const ut = pid ? (productUnitTypes[pid] || 'per_bag') : 'per_bag';
                    this.items[index].unit_type = ut;
                    // Reset quantities when product changes
                    this.items[index].bags = 0;
                    this.items[index].bundles = 0;
                    this.items[index].price = 0;
                    this.items[index].kg = 0;
                    this.items[index].price_per_kg = 0;
                },

                // per_bag: bags → bundles
                calculateBundles(index) {
                    this.items[index].bundles = +(this.items[index].bags * 5).toFixed(4);
                },

                // per_kg: kg → bags (kg/25), bundles (kg/5)
                calculateFromKg(index) {
                    const kg = this.items[index].kg || 0;
                    this.items[index].bags = +(kg / 25).toFixed(6);
                    this.items[index].bundles = +(kg / 5).toFixed(4);
                },

                // per_kg: price_per_kg → unit_price_per_bundle (price_per_kg * 5)
                calculatePriceFromKg(index) {
                    this.items[index].price = +(this.items[index].price_per_kg * 5).toFixed(6);
                },

                calculateSubtotal(index) {
                    const item = this.items[index];
                    if (item.unit_type === 'per_kg') {
                        return ((item.kg || 0) * (item.price_per_kg || 0)).toFixed(2);
                    }
                    return ((item.bundles || 0) * (item.price || 0)).toFixed(2);
                },

                grandTotal() {
                    return this.items.reduce((sum, item) => {
                        if (item.unit_type === 'per_kg') {
                            return sum + ((item.kg || 0) * (item.price_per_kg || 0));
                        }
                        return sum + ((item.bundles || 0) * (item.price || 0));
                    }, 0);
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
