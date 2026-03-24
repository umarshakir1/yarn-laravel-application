<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('User Profile') }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage your account settings and security</p>
            </div>
        </div>
    </x-slot>

    <div class="w-full px-6 lg:px-8 py-8 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            
            <div class="space-y-6">
                {{-- Profile Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Password Security --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white rounded-xl shadow-sm border border-red-50 p-8">
                <div class="max-w-xl">
                    <h2 class="text-lg font-bold text-red-600 mb-1">Danger Zone</h2>
                    <p class="text-xs text-gray-500 mb-6 uppercase tracking-wider">Permanent account actions</p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
