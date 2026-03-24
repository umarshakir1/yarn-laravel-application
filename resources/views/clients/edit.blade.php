<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Client</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $client->name }} — update party info</p>
            </div>
            <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Party Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $client->name) }}" required autofocus
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-1.5">Party Type</label>
                        <select id="type" name="type" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                            <option value="customer" {{ old('type', $client->type) == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="supplier" {{ old('type', $client->type) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="both" {{ old('type', $client->type) == 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $client->phone) }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('phone')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $client->email) }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">
                        @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">Address</label>
                    <textarea id="address" name="address" rows="2"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-colors">{{ old('address', $client->address) }}</textarea>
                </div>

                <!-- Balance Info -->
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Opening Balance</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">{{ number_format($client->opening_balance, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Current Balance</p>
                        <p class="text-lg font-bold font-mono {{ $client->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($client->current_balance, 2) }}</p>
                    </div>
                    <input type="hidden" name="opening_balance" value="{{ $client->opening_balance }}">
                </div>

                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                    <input id="is_active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active Status</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">Update Party</button>
                    <a href="{{ route('clients.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
