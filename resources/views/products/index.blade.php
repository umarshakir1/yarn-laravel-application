<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-sm text-gray-500 mt-0.5">Thread qualities & product catalog</p>
            </div>
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors duration-150 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Product
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Quality</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Unit Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-indigo-50/30 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->quality }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $product->unit_type === 'per_kg' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $product->unit_type === 'per_kg' ? 'Per KG' : 'Per Bag' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg hover:bg-indigo-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-200 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    <p class="text-gray-400 font-medium">No products found.</p>
                                    <a href="{{ route('products.create') }}" class="mt-3 inline-flex items-center gap-1 text-sm text-indigo-600 font-semibold hover:underline">+ Add your first product</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
