<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Staff Management</h1>
                <p class="text-sm text-gray-500 mt-0.5">Create and manage staff accounts with role-based access</p>
            </div>
            <a href="{{ route('staff.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors duration-150 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Staff Member
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
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Allowed Sections</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($staff as $member)
                            <tr class="hover:bg-indigo-50/30 transition-colors even:bg-gray-50/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 text-sm font-bold shrink-0">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $member->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $member->email }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $perms = $member->getPermissionNames();
                                        $labels = [
                                            'view products'  => 'Products',
                                            'view clients'   => 'Parties',
                                            'view purchases' => 'Purchases',
                                            'view sales'     => 'Sales',
                                            'view services'  => 'Services',
                                            'view expenses'  => 'Expenses',
                                            'view accounts'  => 'Bank/Cash',
                                            'view transfers' => 'Transfers',
                                            'view reports'   => 'Reports',
                                        ];
                                    @endphp
                                    @if($perms->isEmpty())
                                        <span class="text-xs text-gray-400 italic">No access assigned</span>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($perms as $perm)
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700">
                                                    {{ $labels[$perm] ?? $perm }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('staff.edit', $member) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg hover:bg-indigo-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('staff.destroy', $member) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete {{ $member->name }}? This cannot be undone.')">
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
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <p class="text-gray-400 font-medium">No staff members yet.</p>
                                    <a href="{{ route('staff.create') }}" class="mt-3 inline-flex items-center gap-1 text-sm text-indigo-600 font-semibold hover:underline">+ Add your first staff member</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
