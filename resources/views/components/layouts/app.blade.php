<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SPPD - Surat Perintah Perjalanan Dinas' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- jQuery (Load before everything) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireChartsScripts
</head>

<body class="font-sans antialiased bg-light">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg shadow-lg border-b border-gray-200"
            x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Left Section: Logo & Brand -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center group">
                            <!-- Logo with gradient -->
                            <div class="relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-primary to-secondary rounded-xl blur opacity-50 group-hover:opacity-75 transition duration-300">
                                </div>
                                <div
                                    class="relative h-12 w-12 bg-gradient-to-br from-primary via-secondary to-accent rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-105 transition duration-300">
                                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <!-- Brand Text -->
                            <div class="ml-4">
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-primary via-secondary to-accent bg-clip-text text-transparent">
                                    Si-SPPD
                                </h1>
                                <p class="text-xs text-muted font-medium">Surat Perjalanan Dinas</p>
                            </div>
                        </div>

                        <!-- Desktop Navigation Links -->
                        <div class="hidden lg:flex lg:ml-10 lg:space-x-2">
                            <a href="{{ route('admin.dashboard') }}"
                                class="group relative px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-gray-600 hover:text-primary' }}">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </span>
                                @if(request()->routeIs('admin.dashboard'))
                                <span
                                    class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-lg"></span>
                                <span
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary to-secondary"></span>
                                @endif
                            </a>
                            {{-- <a href="{{ route('admin.sppd.index') }}"
                                class="group relative px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('admin.sppd.*') ? 'text-primary' : 'text-gray-600 hover:text-primary' }}">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    SPPD
                                </span>
                                @if(request()->routeIs('admin.sppd.*'))
                                <span
                                    class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-lg"></span>
                                <span
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary to-secondary"></span>
                                @endif
                            </a> --}}
                            <a href="{{ route('admin.surat-perintah.index') }}"
                                class="group relative px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('admin.sppd.*') ? 'text-primary' : 'text-gray-600 hover:text-primary' }}">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Surat Perintah
                                </span>
                                @if(request()->routeIs('admin.surat-perintah.*'))
                                <span
                                    class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-lg"></span>
                                <span
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary to-secondary"></span>
                                @endif
                            </a>
                            <a href="{{ route('admin.reports.index') }}"
                                class="group relative px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('admin.reports.*') ? 'text-primary' : 'text-gray-600 hover:text-primary' }}">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Laporan
                                </span>
                                @if(request()->routeIs('admin.reports.*'))
                                <span
                                    class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-lg"></span>
                                <span
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary to-secondary"></span>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Right Section: User & Logout -->
                    <div class="hidden lg:flex lg:items-center lg:gap-4">
                        @if(auth()->check())
                        <!-- User Info Card -->
                        <div
                            class="flex items-center gap-3 px-4 py-2 bg-gradient-to-r from-primary/5 to-secondary/5 rounded-xl border border-primary/10">
                            <div
                                class="h-10 w-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shadow-md">
                                @if(auth()->user()->image)
                                <img src="{{ asset(auth()->user()->image) }}" alt="User Avatar"
                                    onerror="this.onerror=null;this.src='{{ asset('/storage/images/users/default.png') }}';"
                                    class="h-10 w-10 rounded-full object-cover">
                                @else
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-muted">
                                    {{-- {{ auth()->user()->role->name ?? 'N/A' }} --}}
                                    Semesta
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Logout Button -->
                        <button onclick="window.location.href='{{ route('logout') }}'"
                            class="group relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 overflow-hidden">
                            <span
                                class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <svg class="relative h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="relative">Logout</span>
                        </button>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-primary hover:bg-primary/5 focus:outline-none focus:ring-2 focus:ring-primary transition-all duration-300">
                            <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="lg:hidden border-t border-gray-200 bg-white">
                <div class="px-4 pt-4 pb-6 space-y-3">
                    <!-- User Info Mobile -->
                    <div
                        class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-primary/5 to-secondary/5 rounded-xl border border-primary/10 mb-4">
                        <div
                            class="h-12 w-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shadow-md">
                            @if(auth()->user()->image)
                            <img src="{{ asset(auth()->user()->image) }}" alt="User Avatar"
                                onerror="this.onerror=null;this.src='{{ asset('/storage/images/users/default.png') }}';"
                                class="h-10 w-10 rounded-full object-cover">
                            @else
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-muted">
                                {{-- {{ auth()->user()->role->name ?? 'N/A' }} --}}
                                Semesta
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Links Mobile -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-primary/10 to-secondary/10 text-primary' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                        @if(request()->routeIs('admin.dashboard'))
                        <svg class="h-5 w-5 ml-auto text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </a>

                    <a href="{{ route('admin.sppd.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 {{ request()->routeIs('admin.sppd.*') ? 'bg-gradient-to-r from-primary/10 to-secondary/10 text-primary' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>SPPD</span>
                        @if(request()->routeIs('admin.sppd.*'))
                        <svg class="h-5 w-5 ml-auto text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </a>

                    <a href="{{ route('admin.reports.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-300 {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-primary/10 to-secondary/10 text-primary' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Laporan</span>
                        @if(request()->routeIs('admin.reports.*'))
                        <svg class="h-5 w-5 ml-auto text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </a>

                    <!-- Logout Mobile -->
                    <button onclick="window.location.href='{{ route('logout') }}'"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 mt-4">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-gradient-to-r from-primary/5 via-secondary/5 to-accent/5 border-b border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts

    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alert Event Listener -->
    <script>
        window.addEventListener('alert', event => {
            const data = event.detail;
            const type = data.type || data[0]?.type || 'info';
            const message = data.message || data.text || data[0]?.message || '';
            const title = data.title || data[0]?.title || '';

            const config = {
                title: title,
                text: message,
                icon: type,
                position: data.position || 'center',
                showConfirmButton: !data.toast,
                timer: data.timer || (data.toast ? 3000 : undefined),
                toast: data.toast || false,
                timerProgressBar: data.toast || false,
            };

            Swal.fire(config);
        });
    </script>

    @stack('scripts')
</body>

</html>
