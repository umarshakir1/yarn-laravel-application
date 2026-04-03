<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Staff Member</h1>
                <p class="text-sm text-gray-500 mt-0.5">Create a new staff account and assign section access</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8 w-full">
        <form method="POST" action="{{ route('staff.store') }}" class="max-w-2xl space-y-6">
            @csrf

            {{-- Account Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Account Details
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('name') border-red-400 @enderror"
                               placeholder="e.g. Ali Hassan" required autofocus>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('email') border-red-400 @enderror"
                               placeholder="e.g. ali@yourcompany.com" required>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="password">Password</label>
                            <input type="password" id="password" name="password"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('password') border-red-400 @enderror"
                                   placeholder="Min. 6 characters" required>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   placeholder="Repeat password" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Access --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Section Access
                    </h2>
                    <div class="flex gap-3 text-xs">
                        <button type="button" onclick="setAll(true)"
                                class="text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                            Select All
                        </button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="setAll(false)"
                                class="text-gray-500 font-semibold hover:text-gray-700 transition-colors">
                            Clear All
                        </button>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mb-4">Check the sections this staff member is allowed to access. Unchecked sections will be hidden from their navigation.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($sections as $section)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/40 transition-all group has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50">
                            <input type="checkbox" name="permissions[]" value="{{ $section['permission'] }}"
                                   class="w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500 cursor-pointer"
                                   {{ in_array($section['permission'], old('permissions', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-md bg-gray-100 group-[has-input:checked]:bg-indigo-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $section['label'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Staff Member
                </button>
                <a href="{{ route('staff.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-800 transition-colors">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function setAll(checked) {
            document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = checked);
        }
    </script>
    @endpush
</x-app-layout>
