<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Quality: <span class="font-semibold text-gray-700">{{ $product->quality }}</span>
                    &bull;
                    <span class="font-semibold {{ $product->is_active ? 'text-green-600' : 'text-red-500' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('products.edit', $product) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 pb-3 border-b border-gray-100">Basic Information</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Product Name</dt>
                        <dd class="text-lg font-bold text-gray-900 mt-0.5">{{ $product->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Quality</dt>
                        <dd class="text-base font-medium text-gray-800 mt-0.5">{{ $product->quality }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</dt>
                        <dd class="mt-0.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Additional Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 pb-3 border-b border-gray-100">Additional Details</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Description</dt>
                        <dd class="text-sm text-gray-600 italic whitespace-pre-wrap mt-0.5">{{ $product->description ?? 'No description provided.' }}</dd>
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System Info</dt>
                        <dd class="text-xs text-gray-500">Created: {{ $product->created_at->format('M d, Y H:i') }}</dd>
                        <dd class="text-xs text-gray-500 mt-1">Updated: {{ $product->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
