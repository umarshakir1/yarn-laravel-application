<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                <p class="text-sm text-gray-500 mt-0.5">Update product details</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required autofocus
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors outline-none @error('name') border-red-400 @enderror">
                    @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quality" class="block text-sm font-semibold text-gray-700 mb-1.5">Quality <span class="text-red-500">*</span></label>
                        <input id="quality" name="quality" type="text" value="{{ old('quality', $product->quality) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors outline-none @error('quality') border-red-400 @enderror"
                               placeholder="e.g., 50/2, 50/3">
                        @error('quality')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="unit_type" class="block text-sm font-semibold text-gray-700 mb-1.5">Unit Type <span class="text-red-500">*</span></label>
                        <select id="unit_type" name="unit_type" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors outline-none @error('unit_type') border-red-400 @enderror">
                            <option value="per_bag" {{ old('unit_type', $product->unit_type) == 'per_bag' ? 'selected' : '' }}>Per Bag</option>
                            <option value="per_kg" {{ old('unit_type', $product->unit_type) == 'per_kg' ? 'selected' : '' }}>Per KG</option>
                        </select>
                        @error('unit_type')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors outline-none">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                    <input id="is_active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm font-medium text-gray-700">Mark as Active</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                        Update Product
                    </button>
                    <a href="{{ route('products.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
