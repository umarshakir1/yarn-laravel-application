<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">New Sale Invoice</h1>
                <p class="text-sm text-gray-500 mt-0.5">Create a customer sale from available inventory lots</p>
            </div>
            <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full" x-data="saleForm()">
        <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Header Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 pb-3 border-b border-gray-100">Invoice Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Customer / Party <span class="text-red-500">*</span></label>
                        <input type="text" id="client_search" placeholder="Type to filter customers..."
                               class="w-full mb-2 rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-1.5 text-xs focus:border-indigo-400 outline-none transition-all placeholder:text-gray-400">
                        <select id="client_id" name="client_id" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            <option value="">Select Customer</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} (Bal: {{ number_format($client->current_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="sale_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Sale Date <span class="text-red-500">*</span></label>
                        <input id="sale_date" name="sale_date" type="date" value="{{ old('sale_date', date('Y-m-d')) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('sale_date')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="invoice_no" class="block text-sm font-semibold text-gray-700 mb-1.5">Invoice Number <span class="text-red-500">*</span></label>
                        <input id="invoice_no" name="invoice_no" type="text" value="{{ old('invoice_no', 'SAL-' . time()) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('invoice_no')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Sale Items Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Sale Items</h3>
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
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lot Number (Product)</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Available</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Qty to Sell</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <select :name="`items[${index}][lot_id]`" x-model="item.lot_id" @change="updateLotInfo(index); recalculateServices()"
                                                class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none" required>
                                            <option value="">Select Lot</option>
                                            @foreach($lots as $lot)
                                                <option value="{{ $lot->id }}"
                                                        data-bags="{{ $lot->remaining_bags }}"
                                                        data-cost="{{ $lot->cost_price_per_bundle }}"
                                                        data-unit-type="{{ $lot->product->unit_type }}">
                                                    {{ $lot->lot_number }} ({{ $lot->product->name }} - {{ $lot->product->quality }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="mt-1 inline-block text-[10px] font-bold uppercase tracking-wider"
                                              :class="item.unit_type === 'per_kg' ? 'text-blue-500' : 'text-gray-400'"
                                              x-text="item.unit_type === 'per_kg' ? 'Per KG' : 'Per Bag'"></span>
                                    </td>

                                    <!-- Available: per_bag shows bags, per_kg shows kg -->
                                    <td class="px-4 py-3 bg-gray-50/50">
                                        <template x-if="item.unit_type === 'per_bag'">
                                            <span class="text-sm font-bold text-gray-700 font-mono" x-text="item.available_bags + ' bags'"></span>
                                        </template>
                                        <template x-if="item.unit_type === 'per_kg'">
                                            <span class="text-sm font-bold text-blue-700 font-mono" x-text="item.available_kg + ' kg'"></span>
                                        </template>
                                    </td>

                                    <!-- Qty to Sell: per_bag uses bags input, per_kg uses kg input -->
                                    <td class="px-4 py-3">
                                        <!-- Per Bag -->
                                        <template x-if="item.unit_type === 'per_bag'">
                                            <input type="number" :name="`items[${index}][bags]`" x-model.number="item.bags"
                                                   :max="item.available_bags" step="0.01" min="0.01" required
                                                   @input="recalculateServices()"
                                                   placeholder="Bags"
                                                   class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                        </template>
                                        <!-- Per KG -->
                                        <template x-if="item.unit_type === 'per_kg'">
                                            <div>
                                                <input type="number" x-model.number="item.kg"
                                                       :max="item.available_kg" step="0.01" min="0.01" required
                                                       @input="calculateSaleFromKg(index); recalculateServices()"
                                                       placeholder="KG"
                                                       class="block w-full rounded-lg border border-blue-300 px-4 py-2 text-sm font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
                                                <input type="hidden" :name="`items[${index}][bags]`" :value="item.bags" :disabled="item.unit_type !== 'per_kg'">
                                                <input type="hidden" :name="`items[${index}][kg_quantity]`" :value="item.kg">
                                            </div>
                                        </template>
                                    </td>

                                    <!-- Unit Price: per_bag → price/bundle, per_kg → price/kg -->
                                    <td class="px-4 py-3">
                                        <!-- Per Bag -->
                                        <template x-if="item.unit_type === 'per_bag'">
                                            <div>
                                                <label class="block text-[10px] text-gray-400 font-bold uppercase mb-0.5">Price/Bundle</label>
                                                <input type="number" :name="`items[${index}][unit_price_per_bundle]`" x-model.number="item.price"
                                                       step="0.01" min="0" required
                                                       class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                                @if(Auth::user()->hasRole('Admin'))<div class="text-xs text-gray-400 mt-0.5" x-show="item.cost">Cost: <span x-text="item.cost"></span></div>@endif
                                            </div>
                                        </template>
                                        <!-- Per KG -->
                                        <template x-if="item.unit_type === 'per_kg'">
                                            <div>
                                                <label class="block text-[10px] text-blue-500 font-bold uppercase mb-0.5">Price/KG</label>
                                                <input type="number" x-model.number="item.price_per_kg"
                                                       @input="calculateSalePriceFromKg(index)" step="0.01" min="0" required
                                                       class="block w-full rounded-lg border border-blue-300 px-4 py-2 text-sm font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
                                                <input type="hidden" :name="`items[${index}][unit_price_per_bundle]`" :value="item.price" :disabled="item.unit_type !== 'per_kg'">
                                                @if(Auth::user()->hasRole('Admin'))<div class="text-xs text-blue-300 mt-0.5" x-show="item.cost_per_kg">Cost/KG: <span x-text="item.cost_per_kg"></span></div>@endif
                                            </div>
                                        </template>
                                    </td>

                                    <td class="px-4 py-3 text-right bg-gray-50/50">
                                        <span class="text-sm font-bold text-gray-900 font-mono" x-text="calculateSubtotal(index)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(index); recalculateServices()" class="text-red-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════
                 ADDITIONAL SERVICES SECTION
            ═══════════════════════════════════════════════════════ -->
            @if($services->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" 
                 x-show="totalBags() > 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="px-6 py-4 bg-indigo-700 flex items-center gap-3">
                    <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Additional Services</h3>
                    <span class="ml-auto text-xs text-indigo-300 font-semibold" x-text="`Quantity: ${totalBags()} Bags`"></span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($services as $service)
                        <div class="service-card-wrapper flex flex-col p-4 rounded-xl border-2 border-gray-100 bg-gray-50/50 hover:border-indigo-300 hover:bg-indigo-50/40 transition-all duration-150 group"
                             :class="selectedServices.includes({{ $service->id }}) ? 'border-indigo-500 bg-indigo-50 shadow-sm' : ''">
                            
                            <div class="flex items-center gap-3 mb-2">
                                <input id="service_{{ $service->id }}"
                                       type="checkbox"
                                       name="services[]"
                                       value="{{ $service->id }}"
                                       data-price="{{ $service->price }}"
                                       data-cost="{{ $service->cost_price }}"
                                       data-unit="{{ $service->unit }}"
                                       data-name="{{ $service->name }}"
                                       @change="toggleService({{ $service->id }}, $event)"
                                       class="service-checkbox w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer shrink-0">
                                <div class="min-w-0 flex-1">
                                    <label for="service_{{ $service->id }}" class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors cursor-pointer">
                                        {{ $service->name }}
                                    </label>
                                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">
                                        Default: Rs {{ number_format($service->price, 2) }} / {{ str_replace('_', ' ', $service->unit) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Editable Price (always visible) -->
                            <div class="mt-1">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Price / {{ str_replace('_', ' ', $service->unit) }}</label>
                                <input type="number"
                                       id="service_price_{{ $service->id }}"
                                       name="service_prices[{{ $service->id }}]"
                                       value="{{ $service->price }}"
                                       step="0.01" min="0"
                                       @input="updateServicePrice({{ $service->id }}, $event.target.value)"
                                       class="service-price-input w-full rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-mono text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            </div>

                            <!-- Individual Service Total (Visible when checked) -->
                            <div x-show="selectedServices.includes({{ $service->id }})" 
                                 x-transition:enter="transition duration-150"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 class="mt-2 pt-2 border-t border-indigo-100/50 flex justify-between items-center font-mono">
                                <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">Total:</span>
                                <span class="text-sm font-black text-indigo-800" x-text="formatCurrency(calculatedValues[{{ $service->id }}] || 0)"></span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Services subtotal line -->
                    <div x-show="servicesTotal > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-4 flex items-center justify-between p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                        <span class="text-sm font-semibold text-indigo-700">
                            Services Subtotal
                            <span class="text-xs text-indigo-400 font-normal" x-text="`(${selectedServices.length} selected)`"></span>
                        </span>
                        <span class="font-black text-indigo-800 font-mono text-sm" x-text="formatCurrency(servicesTotal)"></span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Summary & Payment -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Notes / Remarks</label>
                        <textarea id="notes" name="notes" rows="5"
                                  placeholder="Any specific details..."
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors"></textarea>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500 font-semibold">Items Subtotal:</span>
                            <span class="font-bold text-gray-900 font-mono" x-text="formatCurrency(itemsTotal())"></span>
                        </div>
                        <div x-show="servicesTotal > 0" class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-sm text-indigo-600 font-semibold">Services:</span>
                            <span class="font-bold text-indigo-700 font-mono" x-text="formatCurrency(servicesTotal)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-red-600 font-semibold">Discount:</span>
                            <input type="number" name="discount" x-model.number="discount" step="0.01"
                                   class="w-32 text-right rounded-lg border border-gray-300 px-4 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                        </div>
                        <div class="flex justify-between items-center text-xl font-bold border-t border-gray-100 pt-3">
                            <span class="text-gray-600">Grand Total:</span>
                            <span class="font-black text-gray-900 font-mono" x-text="formatCurrency(grandTotal())"></span>
                        </div>
                        <div>
                            <label for="paid_amount" class="block text-sm font-semibold text-gray-700 mb-1.5">Paid Amount <span class="text-red-500">*</span></label>
                            <input id="paid_amount" name="paid_amount" type="number" step="0.01" placeholder="0.00" required
                                   x-model.number="paidAmount"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base font-bold font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        </div>
                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <span class="text-sm font-semibold text-blue-700">Remaining (Receivable):</span>
                            <span class="text-base font-black text-blue-700 font-mono" x-text="formatCurrency(grandTotal() - paidAmount)"></span>
                        </div>
                        <button type="submit"
                                class="w-full py-3 bg-gray-900 text-white text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                            Submit Sale Invoice
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function newSaleItem() {
            return {
                lot_id: '', unit_type: 'per_bag',
                available_bags: 0, available_kg: 0,
                cost: 0, cost_per_kg: 0,
                bags: 0, price: 0,
                kg: 0, price_per_kg: 0,
            };
        }

        function saleForm() {
            return {
                items: [newSaleItem()],
                paidAmount: 0,
                discount: 0,
                servicesTotal: 0,
                selectedServices: [],
                calculatedValues: {}, // Stores individual totals per service
                servicePrices: {},    // Overridden price per service id

                addItem() {
                    this.items.push(newSaleItem());
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                updateLotInfo(index) {
                    const select = document.getElementsByName(`items[${index}][lot_id]`)[0];
                    const option = select.options[select.selectedIndex];
                    if (option.value) {
                        const remainingBags = parseFloat(option.dataset.bags) || 0;
                        const costPerBundle  = parseFloat(option.dataset.cost)  || 0;
                        const unitType       = option.dataset.unitType || 'per_bag';

                        this.items[index].available_bags = remainingBags;
                        this.items[index].available_kg   = +(remainingBags * 25).toFixed(4);
                        this.items[index].cost           = costPerBundle;
                        this.items[index].cost_per_kg    = +(costPerBundle / 5).toFixed(6);
                        this.items[index].unit_type      = unitType;
                        // Reset qty/price on lot change
                        this.items[index].bags         = 0;
                        this.items[index].price        = 0;
                        this.items[index].kg           = 0;
                        this.items[index].price_per_kg = 0;
                    } else {
                        Object.assign(this.items[index], newSaleItem());
                    }
                },

                // per_kg sale: kg → bags (kg/25)
                calculateSaleFromKg(index) {
                    const kg = this.items[index].kg || 0;
                    this.items[index].bags = +(kg / 25).toFixed(6);
                },

                // per_kg sale: price_per_kg → unit_price_per_bundle (price_per_kg * 5)
                calculateSalePriceFromKg(index) {
                    this.items[index].price = +(this.items[index].price_per_kg * 5).toFixed(6);
                },

                // totalBags sums all item bags (fractional for per_kg items) — used by services
                totalBags() {
                    return this.items.reduce((sum, item) => sum + (item.lot_id ? parseFloat(item.bags || 0) : 0), 0);
                },

                toggleService(serviceId, event) {
                    if (event.target.checked) {
                        this.selectedServices.push(serviceId);
                    } else {
                        this.selectedServices = this.selectedServices.filter(id => id !== serviceId);
                    }
                    this.recalculateServices();
                },

                updateServicePrice(serviceId, value) {
                    this.servicePrices[serviceId] = parseFloat(value) || 0;
                    this.recalculateServices();
                },

                recalculateServices() {
                    let total = 0;
                    const bags = this.totalBags();

                    this.calculatedValues = {};

                    document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                        const sId = parseInt(checkbox.value);
                        if (this.selectedServices.includes(sId)) {
                            const unit = checkbox.dataset.unit;
                            const priceInput = document.getElementById(`service_price_${sId}`);
                            const unitPrice = priceInput ? (parseFloat(priceInput.value) || 0) : parseFloat(checkbox.dataset.price);

                            let multiplier = 1;
                            if (unit === 'per_kg') multiplier = 25;
                            else if (unit === 'per_bundle') multiplier = 5;

                            const lineTotal = unitPrice * bags * multiplier;
                            this.calculatedValues[sId] = lineTotal;
                            total += lineTotal;
                        }
                    });

                    this.servicesTotal = Math.round(total * 100) / 100;
                },

                calculateSubtotal(index) {
                    const item = this.items[index];
                    if (item.unit_type === 'per_kg') {
                        return ((item.kg || 0) * (item.price_per_kg || 0)).toFixed(2);
                    }
                    return ((item.bags || 0) * 5 * (item.price || 0)).toFixed(2);
                },

                itemsTotal() {
                    return this.items.reduce((sum, item) => {
                        if (item.unit_type === 'per_kg') {
                            return sum + ((item.kg || 0) * (item.price_per_kg || 0));
                        }
                        return sum + ((item.bags || 0) * 5 * (item.price || 0));
                    }, 0);
                },

                grandTotal() {
                    return this.itemsTotal() + this.servicesTotal - this.discount;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('en-PK', { style: 'currency', currency: 'PKR' }).format(value);
                }
            }
        }

        // Customer Search Filter Logic
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('client_search');
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
