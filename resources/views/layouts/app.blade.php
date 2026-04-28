<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" href="{{ asset('images/icons/android/android-launchericon-96-96.png') }}">
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .content-transition { transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
    @laravelPWA
</head>
<body class="font-sans antialiased">
    
    <div class="min-h-screen bg-gray-100" 
         x-data="{ 
            isMobileMenuOpen: false, 
            isDesktopMini: localStorage.getItem('sidebarMini') === 'true',
            
            toggleDesktop() {
                this.isDesktopMini = !this.isDesktopMini;
                localStorage.setItem('sidebarMini', this.isDesktopMini);
            },
            
            // Fungsi helper: Jika menu dropdown diklik saat mode mini, otomatis perbesar sidebar
            expandIfMini() {
                if(this.isDesktopMini) {
                    this.toggleDesktop();
                }
            }
         }">
        
        <aside
            class="fixed inset-y-0 left-0 z-40 bg-white border-r border-gray-200 shadow-sm sidebar-transition flex flex-col"
            :class="{
                'translate-x-0 w-64': isMobileMenuOpen,       /* Mobile: Open */
                '-translate-x-full w-64': !isMobileMenuOpen,  /* Mobile: Closed */
                'lg:translate-x-0': true,                     /* Desktop: Always visible */
                'lg:w-20': isDesktopMini,                     /* Desktop: Mini Width */
                'lg:w-64': !isDesktopMini                     /* Desktop: Full Width */
            }">

            <button 
                id="tour-sidebar-toggle"
                @click="toggleDesktop()"
                class="hidden lg:flex absolute -right-3 top-20 bg-white border border-gray-200 text-gray-500 hover:text-red-600 rounded-full w-6 h-6 items-center justify-center shadow-sm z-50 transition-colors"
                title="Toggle Sidebar">
                <i class="fa-solid fa-chevron-left text-xs transition-transform duration-300"
                   :class="isDesktopMini ? 'rotate-180' : ''"></i>
            </button>

            {{-- Logo Area --}}
            <div class="flex items-center h-16 border-b border-gray-200 shrink-0 transition-all duration-300 overflow-hidden"
                 :class="isDesktopMini ? 'justify-center px-0' : 'justify-center px-4'">
                <a href="{{ route('dashboard') }}" class="flex items-center whitespace-nowrap overflow-hidden">
                    <i class="fa-solid fa-cubes-stacked text-3xl text-red-600 shrink-0"></i>
                    <span class="font-bold text-2xl text-gray-800 ml-2 transition-opacity duration-300"
                          :class="isDesktopMini ? 'lg:opacity-0 lg:hidden' : 'opacity-100'">
                        PGA System
                    </span>
                </a>
            </div>

            {{-- Navigation Links Wrapper --}}
            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 custom-scrollbar">
                <ul class="space-y-1 px-3">
                    
                    <li>
                        <a id="tour-menu-dashboard" href="{{route('dashboard')}}" 
                           class="flex items-center gap-4 py-3 rounded-lg transition-all duration-300 group
                           {{ request()->routeIs('dashboard') ? 'text-red-600 bg-red-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}
                           "
                           :class="isDesktopMini ? 'justify-center px-0' : 'px-4'">
                            
                            <i class="w-5 text-center fa-solid fa-border-all text-lg shrink-0"></i>
                            
                            <span class="font-semibold whitespace-nowrap transition-all duration-300 origin-left"
                                  :class="isDesktopMini ? 'lg:w-0 lg:opacity-0 lg:overflow-hidden' : 'w-auto opacity-100'">
                                Dashboard
                            </span>

                            <div x-show="isDesktopMini" class="hidden lg:group-hover:block absolute left-16 bg-gray-800 text-white text-xs px-2 py-1 rounded ml-2 whitespace-nowrap z-50">
                                Dashboard
                            </div>
                        </a>
                    </li>

                    @can('view_master_data')
                        <li id="tour-menu-master" x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('departments.*') ? 'true' : 'false' }} }">
                            <button id="btn-master-toggle" @click="expandIfMini(); open = !open" 
                                    class="w-full flex items-center py-3 rounded-lg font-medium transition-all duration-300 group text-gray-600 hover:bg-gray-100 hover:text-gray-900"
                                    :class="isDesktopMini ? 'justify-center px-0' : 'justify-between px-4'">
                                
                                <div class="flex items-center gap-4">
                                    <i class="w-5 text-center fa-solid fa-database text-lg shrink-0"></i>
                                    <span class="whitespace-nowrap transition-all duration-300 origin-left"
                                          :class="isDesktopMini ? 'lg:w-0 lg:opacity-0 lg:overflow-hidden' : 'w-auto opacity-100'">
                                        Master Data
                                    </span>
                                </div>
                                
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 shrink-0" 
                                   :class="{ 'rotate-180': open, 'lg:hidden': isDesktopMini }"></i>
                            </button>

                            <ul id="submenu-master-list" x-show="open && !isDesktopMini" 
                                x-collapse
                                class="space-y-1 mt-1 overflow-hidden"
                                :class="isDesktopMini ? 'lg:hidden' : 'pl-11 pr-2'"> <li>
                                    <a href="{{ route('users.index') }}" 
                                       class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('users.*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                                        Users
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('departments.index') }}" 
                                       class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('departments.*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                                        Departments
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan


                    @role('Admin')
                        <li>
                            <a id="tour-menu-roleaccess" href="{{ route('roles.index') }}" 
                               class="flex items-center gap-4 py-3 rounded-lg font-medium transition-all duration-300 group
                               {{ request()->routeIs('roles.*') ? 'text-red-600 bg-red-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}
                               "
                               :class="isDesktopMini ? 'justify-center px-0' : 'px-4'">
                                
                                <i class="w-5 text-center fa-solid fa-user-shield text-lg shrink-0"></i>
                                <span class="whitespace-nowrap transition-all duration-300 origin-left"
                                      :class="isDesktopMini ? 'lg:w-0 lg:opacity-0 lg:overflow-hidden' : 'w-auto opacity-100'">
                                    Role & Access
                                </span>
                            </a>
                        </li>
                    @endrole
                    @can('view_system_logs') 
                        <li id="tour-menu-monitoring" x-data="{ open: {{ request()->routeIs('activity-logs.*') || request()->routeIs('reservasi-stats.*') ? 'true' : 'false' }} }">
                            <button id="btn-monitoring-toggle" @click="expandIfMini(); open = !open" 
                                    class="w-full flex items-center py-3 rounded-lg font-medium transition-all duration-300 group text-gray-600 hover:bg-gray-100 hover:text-gray-900"
                                    :class="isDesktopMini ? 'justify-center px-0' : 'justify-between px-4'">
                                
                                <div class="flex items-center gap-4">
                                    {{-- Icon Monitoring / Chart --}}
                                    <i class="w-5 text-center fa-solid fa-chart-line text-lg shrink-0"></i>
                                    <span class="whitespace-nowrap transition-all duration-300 origin-left"
                                          :class="isDesktopMini ? 'lg:w-0 lg:opacity-0 lg:overflow-hidden' : 'w-auto opacity-100'">
                                        Monitoring
                                    </span>
                                </div>
                                
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 shrink-0" 
                                   :class="{ 'rotate-180': open, 'lg:hidden': isDesktopMini }"></i>
                            </button>

                            <ul id="submenu-monitoring-list" x-show="open && !isDesktopMini" 
                                x-collapse
                                class="space-y-1 mt-1 overflow-hidden"
                                :class="isDesktopMini ? 'lg:hidden' : 'pl-11 pr-2'">
                                
                                {{-- Menu 1: Login Activity --}}
                                <li>
                                    <a href="{{ route('activity-logs.index') }}" 
                                       class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('activity-logs.*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                                        Login Activity
                                    </a>
                                </li>

                                {{-- Menu 2: Reservasi Stats --}}
                                <li>
                                    <a href="{{ route('reservasi-stats.index') }}" 
                                       class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('reservasi-stats.*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                                        Public Stats
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    <li>
                        <a id="tour-menu-visitor" href="{{ route('visitors.index') }}" 
                           class="flex items-center gap-4 py-3 rounded-lg font-medium transition-all duration-300 group
                           {{ request()->routeIs('visitors.*') ? 'text-red-600 bg-red-50' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}
                           "
                           :class="isDesktopMini ? 'justify-center px-0' : 'px-4'">
                            
                            <i class="w-5 text-center fa-solid fa-users text-lg shrink-0"></i>
                            <span class="whitespace-nowrap transition-all duration-300 origin-left"
                                  :class="isDesktopMini ? 'lg:w-0 lg:opacity-0 lg:overflow-hidden' : 'w-auto opacity-100'">
                                Data Visitor
                            </span>
                        </a>
                    </li>

                </ul>
            </nav>

            <div class="border-t border-gray-200 p-4 shrink-0 overflow-hidden"
                 :class="isDesktopMini ? 'hidden lg:block' : 'block'">
                <div class="flex flex-col items-center justify-center text-center opacity-80 hover:opacity-100 transition-opacity duration-300">
                    <i class="fa-solid fa-code text-gray-300 text-xs" 
                       :class="isDesktopMini ? 'block mb-0' : 'mb-2'"></i>
                    
                    <p class="text-[10px] leading-relaxed text-gray-400 font-medium select-none transition-all duration-300 whitespace-nowrap"
                       :class="isDesktopMini ? 'lg:w-0 lg:h-0 lg:opacity-0 lg:overflow-hidden' : 'w-auto opacity-100'">
                        All system develop by<br>
                        <span class="font-bold text-gray-500">Team 4.0</span><br>
                        Plant - Salatiga
                    </p>
                </div>
            </div>
        </aside>

        <div x-show="isMobileMenuOpen" 
             @click="isMobileMenuOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-black/50 lg:hidden"
             style="display: none;">
        </div>

        <div class="flex flex-col flex-1 min-h-screen content-transition duration-300"
             :class="isDesktopMini ? 'lg:pl-20' : 'lg:pl-64'">
            
            <header class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm" x-data="{ userDropdownOpen: false }">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        
                        <div class="flex items-center lg:hidden">
                            <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition">
                                <i class="fa-solid fa-bars text-xl"></i>
                            </button>
                        </div>

                        <div class="flex-1"></div>

                        <div class="flex items-center ms-6 relative">
                            <button id="tour-profile-dropdown" @click="userDropdownOpen = !userDropdownOpen" 
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm leading-4 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-200 transition ease-in-out duration-150">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold border border-blue-100">
                                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="hidden sm:flex items-center gap-2">
                                        <span class="font-semibold text-gray-700">{{ Auth::user()->name ?? 'User' }}</span>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200" :class="{'rotate-180': userDropdownOpen}"></i>
                                    </div>
                                </div>
                            </button>

                            <div id="profile-dropdown-menu" x-show="userDropdownOpen" 
                                @click.away="userDropdownOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 top-14 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-2 z-50 border border-gray-100"
                                style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-gray-50 mb-1">
                                    <p class="text-sm font-bold text-gray-800">Signed in as</p>
                                    <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors flex items-center gap-3">
                                    <i class="fa-regular fa-user w-4 text-gray-400"></i>
                                    Profile
                                </a>

                                <a href="{{ route('password.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors flex items-center gap-3">
                                    <i class="fa-solid fa-lock w-4 text-gray-400"></i>
                                    Ubah Password
                                </a>

                                <div class="border-t border-gray-50 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" 
                                    onclick="event.preventDefault(); this.closest('form').submit();" 
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors flex items-center gap-3">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-gray-400"></i>
                                        Log Out
                                    </a>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>