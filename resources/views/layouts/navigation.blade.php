<nav x-data="{ open: false }" class="bg-gray-900 shadow-lg sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center mr-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-white font-bold text-sm tracking-wide hidden lg:block">Trading ERP</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:space-x-1">
                    @php
                        $authUser   = Auth::user();
                        $isAdmin    = $authUser->hasRole('Admin');
                        $navLinks   = [
                            ['route' => 'dashboard',        'label' => 'Dashboard',  'match' => 'dashboard',    'permission' => null],
                            ['route' => 'products.index',   'label' => 'Products',   'match' => 'products.*',   'permission' => 'view products'],
                            ['route' => 'clients.index',    'label' => 'Parties',    'match' => 'clients.*',    'permission' => 'view clients'],
                            ['route' => 'purchases.index',  'label' => 'Purchases',  'match' => 'purchases.*',  'permission' => 'view purchases'],
                            ['route' => 'sales.index',      'label' => 'Sales',      'match' => 'sales.*',      'permission' => 'view sales'],
                            ['route' => 'services.index',   'label' => 'Services',   'match' => 'services.*',   'permission' => 'view services'],
                            ['route' => 'expenses.index',   'label' => 'Expenses',   'match' => 'expenses.*',   'permission' => 'view expenses'],
                            ['route' => 'accounts.index',   'label' => 'Bank/Cash',  'match' => 'accounts.*',   'permission' => 'view accounts'],
                            ['route' => 'transfers.index',  'label' => 'Transfers',  'match' => 'transfers.*',  'permission' => 'view transfers'],
                        ];
                    @endphp
                    @foreach($navLinks as $link)
                        @if(is_null($link['permission']) || $isAdmin || $authUser->hasPermissionTo($link['permission']))
                            <a href="{{ route($link['route']) }}"
                               class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                                      {{ request()->routeIs($link['match'])
                                        ? 'bg-gray-800 text-white'
                                        : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                                {{ $link['label'] }}
                            </a>
                        @endif
                    @endforeach

                    <!-- Reports Dropdown -->
                    @php
                        $canLedgers = $isAdmin || $authUser->hasPermissionTo('view clients');
                    @endphp
                    @if($isAdmin || $canLedgers)
                    <div x-data="{ reportsOpen: false }" class="relative">
                        <button @click="reportsOpen = !reportsOpen" @click.away="reportsOpen = false"
                                class="flex items-center gap-1 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                                       {{ request()->routeIs('reports.*') || request()->routeIs('ledgers.*')
                                         ? 'bg-gray-800 text-white'
                                         : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                            Reports
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': reportsOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="reportsOpen"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50"
                             style="display: none;">
                            @if($isAdmin)
                            <div class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Reports</div>
                            <a href="{{ route('reports.sales') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Sales Report
                            </a>
                            <a href="{{ route('reports.inventory') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                Inventory Report
                            </a>
                            <a href="{{ route('reports.profit_loss') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                P&L Report
                            </a>
                            @endif
                            @if($canLedgers)
                            @if($isAdmin)<div class="border-t border-gray-100 my-1"></div>@endif
                            <div class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Ledgers</div>
                            <a href="{{ route('ledgers.customers.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Customer Ledgers
                            </a>
                            <a href="{{ route('ledgers.suppliers.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Supplier Ledgers
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Staff Management (Admin only) -->
                    @if($isAdmin)
                        <a href="{{ route('staff.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150
                                  {{ request()->routeIs('staff.*')
                                    ? 'bg-gray-800 text-white'
                                    : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                            Staff
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (User Menu) -->
            <div class="hidden sm:flex sm:items-center">
                <div x-data="{ userOpen: false }" class="relative">
                    <button @click="userOpen = !userOpen" @click.away="userOpen = false"
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 transition-colors duration-150">
                        <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="hidden lg:block">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" :class="{'rotate-180': userOpen}" class="transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="userOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-800 border-t border-gray-700">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @foreach($navLinks as $link)
                @if(is_null($link['permission']) || $isAdmin || $authUser->hasPermissionTo($link['permission']))
                    <a href="{{ route($link['route']) }}"
                       class="block px-3 py-2 rounded-md text-sm font-medium transition-colors
                              {{ request()->routeIs($link['match']) ? 'bg-gray-700 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        {{ $link['label'] }}
                    </a>
                @endif
            @endforeach

            @if($isAdmin || $canLedgers)
            <div class="border-t border-gray-700 pt-2 mt-2">
                @if($isAdmin)
                <div class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Reports</div>
                <a href="{{ route('reports.sales') }}" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-700">Sales Report</a>
                <a href="{{ route('reports.inventory') }}" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-700">Inventory Report</a>
                <a href="{{ route('reports.profit_loss') }}" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-700">P&L Report</a>
                @endif
                @if($canLedgers)
                <div class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider {{ $isAdmin ? 'mt-2' : '' }}">Ledgers</div>
                <a href="{{ route('ledgers.customers.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-700">Customer Ledgers</a>
                <a href="{{ route('ledgers.suppliers.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-700">Supplier Ledgers</a>
                @endif
            </div>
            @endif

            @if($isAdmin)
            <div class="border-t border-gray-700 pt-2 mt-2">
                <a href="{{ route('staff.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-700">Staff Management</a>
            </div>
            @endif
        </div>

        <!-- Responsive User Options -->
        <div class="pt-4 pb-3 border-t border-gray-700 px-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm text-gray-300 hover:text-white hover:bg-gray-700">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-sm text-red-400 hover:text-white hover:bg-red-700 transition-colors">Log Out</button>
            </form>
        </div>
    </div>
</nav>
