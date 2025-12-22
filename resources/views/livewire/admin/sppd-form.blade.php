<div class="min-h-screen bg-light py-8" x-data="{ currentTab: '{{ $currentTab }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    {{ $isEdit ? 'Edit SPPD' : 'Buat SPPD Baru' }}
                </h2>
                <p class="mt-2 text-sm text-muted">Formulir Surat Perintah Perjalanan Dinas</p>
            </div>
            <a href="{{ route('admin.surat-perintah.sppd', ['id' => $suratPerintahId]) }}" class="btn-secondary">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Daftar SPPD
            </a>
        </div>

        <!-- Form with Tabs -->
        <div class="card">
            <!-- Tab Navigation -->
            {{-- <div class="border-b border-gray-200 bg-gradient-to-r from-primary/5 to-secondary/10">
                <div class="overflow-x-auto scrollbar-hide">
                    <nav class="tab-nav px-4" aria-label="Tabs">
                        <!-- Tab 1: Pejabat -->
                        <button type="button" wire:click="$set('currentTab', 'pejabat')"
                            class="{{ $currentTab == 'pejabat' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden sm:inline">Pejabat Pemberi Perintah</span>
                            <span class="sm:hidden">Pejabat</span>
                        </button>

                        <!-- Tab 2: Pegawai -->
                        <button type="button" wire:click="$set('currentTab', 'pegawai')"
                            class="{{ $currentTab == 'pegawai' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="hidden sm:inline">Pegawai Pelaksana</span>
                            <span class="sm:hidden">Pegawai</span>
                        </button>

                        <!-- Tab 3: Detail -->
                        <button type="button" wire:click="$set('currentTab', 'detail')"
                            class="{{ $currentTab == 'detail' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="hidden sm:inline">Detail Perjalanan</span>
                            <span class="sm:hidden">Detail</span>
                        </button>

                        <!-- Tab 4: Biaya -->
                        <button type="button" wire:click="$set('currentTab', 'biaya')"
                            class="{{ $currentTab == 'biaya' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Biaya</span>
                        </button>

                        <!-- Tab 5: Preview -->
                        <a href="{{ route('admin.sppd.preview', $sppdId) }}"
                            class="{{ $currentTab == 'preview' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Preview</span>
                        </a>
                    </nav>
                </div>
            </div> --}}

            <form wire:submit.prevent="save">
                <div class="">
                    <!-- Tab Content -->
                    <!-- Tab 1: Pejabat Pemberi Perintah -->
                    <div
                        class="{{ $currentTab == 'pejabat' ? '' : 'hidden' }} bg-white rounded-xl shadow-xl overflow-hidden">

                        <div class="card-header">
                            <h3
                                class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent mb-2 flex items-center">
                                <svg class="h-6 w-6 mr-3 text-primary" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Pejabat yang Memberi Perintah
                            </h3>
                            <p class="text-sm text-muted">Pilih pejabat yang memberikan perintah perjalanan dinas</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Pilih Instansi untuk API -->
                            <div class="">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Instansi
                                </label>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="flex-1" wire:ignore>
                                        <select wire:model="selectedInstanceGiver" class="select2 form-input"
                                            data-placeholder="-- Pilih Instansi --" id="instanceGiverSelect">
                                            <option value="">-- Pilih Instansi --</option>
                                            @foreach($instances as $inst)
                                            <option value="{{ $inst['id'] }}">{{ $inst['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($selectedInstanceGiver != 0)
                                    <button type="button" wire:click="fetchSemestaOfficers" wire:loading.attr="disabled"
                                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                                        <span wire:loading.remove wire:target="fetchSemestaOfficers"
                                            class="flex items-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>
                                                Ambil Data
                                            </span>
                                        </span>
                                        <span wire:loading wire:target="fetchSemestaOfficers">
                                            <svg class="animate-spin h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                    @endif

                                </div>
                                @error('commanding_officer')
                                <div class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Daftar Pejabat dari API -->
                            @if(count($semestaOfficers) > 0)
                            <div class="mb-4 p-4 bg-blue-light bg-opacity-20 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Pejabat dari Semesta
                                </label>

                                <!-- Search Input for Officers -->
                                <div class="mb-3 flex items-center justify-between">
                                    <div class="relative flex-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input wire:model.live="searchOfficer" type="text"
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy text-sm"
                                            placeholder="Cari nama, NIP, atau jabatan pejabat...">
                                    </div>
                                    {{-- toggle button --}}
                                    <button type="button" wire:click="toggleShowOfficers"
                                        class="ml-2 px-3 py-1 bg-gray-200 rounded-lg text-sm">
                                        {{ $showOfficers ? 'Sembunyikan Pejabat' : 'Tampilkan Pejabat' }}
                                    </button>
                                </div>

                                @if($showOfficers)
                                <div class="max-h-60 overflow-y-auto space-y-2">
                                    @foreach($semestaOfficers as $officer)
                                    <div wire:click="selectOfficer({{ json_encode($officer) }})"
                                        class="p-3 bg-white border border-gray-200 rounded-lg hover:bg-navy hover:text-white cursor-pointer transition duration-150">
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium">
                                                {{ $officer['nama_lengkap'] ?? 'N/A' }}
                                            </p>
                                            @if($officer['kepala_skpd'] == 'Y')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Kepala OPD / Bagian
                                            </span>
                                            @endif
                                        </div>
                                        <p class="text-sm opacity-75">
                                            NIP. {{ $officer['nip'] ?? 'N/A' }} | {{ $officer['jabatan'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Informasi Pejabat yang Dipilih -->
                            @if($selectedOfficer)
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="text-sm font-semibold text-green-800">Pejabat Terpilih</h4>
                                        </div>
                                        <div class="space-y-1 text-sm">
                                            <p class="text-gray-900">
                                                <span class="font-medium">Nama:</span>
                                                <span class="text-navy font-semibold">{{
                                                    $selectedOfficer['nama_lengkap'] ??
                                                    'N/A' }}</span>
                                                @if(isset($selectedOfficer['kepala_skpd']) &&
                                                $selectedOfficer['kepala_skpd'] == 'Y')
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Kepala OPD
                                                </span>
                                                @endif
                                            </p>
                                            <p class="text-gray-700">
                                                <span class="font-medium">NIP.</span> {{ $selectedOfficer['nip'] ??
                                                'N/A' }}
                                            </p>
                                            <p class="text-gray-700">
                                                <span class="font-medium">Jabatan:</span> {{ $selectedOfficer['jabatan']
                                                ??
                                                'N/A' }}
                                            </p>
                                            @if(isset($selectedOfficer['pangkat']) && $selectedOfficer['pangkat'])
                                            <p class="text-gray-700">
                                                <span class="font-medium">Pangkat:</span> {{ $selectedOfficer['pangkat']
                                                }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <button type="button" wire:click="$set('selectedOfficer', null)"
                                        class="ml-4 text-gray-400 hover:text-red-600 transition duration-150">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button> --}}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 2: Pegawai yang Melaksanakan -->
                    <div
                        class="{{ $currentTab == 'pegawai' ? '' : 'hidden' }} bg-white rounded-xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-navy/5 to-blue-light/10 p-6 border-b-2 border-navy/20">
                            <h3 class="text-xl font-bold text-navy flex items-center gap-2">
                                <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Pegawai yang Melaksanakan Perjalanan Dinas
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">

                            <!-- Pilih Instansi untuk API -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Instansi
                                </label>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="flex-1" wire:ignore>
                                        <select wire:model="selectedInstance" class="select2 form-input"
                                            data-placeholder="-- Pilih Instansi --" id="instanceSelect">
                                            <option value="">-- Pilih Instansi --</option>
                                            @foreach(collect($instances)->where('id', '!=', 0) as $inst)
                                            <option value="{{ $inst['id'] }}">{{ $inst['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" wire:click="fetchSemestaUsers" wire:loading.attr="disabled"
                                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                                        <span wire:loading.remove wire:target="fetchSemestaUsers"
                                            class="flex items-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>
                                                Ambil Data
                                            </span>
                                        </span>
                                        <span wire:loading wire:target="fetchSemestaUsers">
                                            <svg class="animate-spin h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                                @error('employee_id')
                                <div class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Daftar Pegawai dari API -->
                            @if(count($semestaUsers) > 0)
                            <div class="mb-4 p-4 bg-blue-light bg-opacity-20 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Pegawai dari Semesta
                                </label>

                                <!-- Search Input for Employees -->
                                <div class="mb-3 flex items-center justify-between">
                                    <div class="relative flex-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input wire:model.live="searchEmployee" type="text"
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy text-sm"
                                            placeholder="Cari nama, NIP, atau jabatan pegawai...">
                                    </div>
                                    {{-- toggle button --}}
                                    <button type="button" wire:click="toggleShowEmployees"
                                        class="ml-2 px-3 py-1 bg-gray-200 rounded-lg text-sm">
                                        {{ $showEmployees ? 'Sembunyikan Pegawai' : 'Tampilkan Pegawai' }}
                                    </button>
                                </div>

                                @if($showEmployees)
                                <div class="max-h-60 overflow-y-auto space-y-2">
                                    @foreach($semestaUsers as $user)
                                    <div wire:click="selectEmployee({{ json_encode($user) }})"
                                        class="p-3 bg-white border border-gray-200 rounded-lg hover:bg-navy hover:text-white cursor-pointer transition duration-150">

                                        <div class="flex items-center gap-2">
                                            <p class="font-medium">
                                                {{ $user['nama_lengkap'] ?? 'N/A' }}
                                            </p>
                                            @if($user['kepala_skpd'] == 'Y')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Kepala OPD / Bagian
                                            </span>
                                            @endif
                                        </div>
                                        <p class="text-sm opacity-75">
                                            NIP. {{ $user['nip'] ?? 'N/A' }} | {{ $user['jabatan']
                                            ?? 'N/A' }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Informasi Pegawai yang Dipilih -->
                            @if($selectedEmployee)
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <h4 class="text-sm font-semibold text-blue-800">
                                                Pegawai yang Melaksanakan Perjalanan Dinas
                                            </h4>
                                        </div>
                                        <div class="space-y-1 text-sm">
                                            <p class="text-gray-900">
                                                <span class="font-medium">Nama:</span>
                                                <span class="text-navy font-semibold">{{
                                                    $selectedEmployee['nama_lengkap']
                                                    ??
                                                    'N/A' }}</span>
                                                @if(isset($selectedEmployee['kepala_skpd']) &&
                                                $selectedEmployee['kepala_skpd'] == 'Y')
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Kepala OPD
                                                </span>
                                                @endif
                                            </p>
                                            <p class="text-gray-700">
                                                <span class="font-medium">NIP.</span> {{ $selectedEmployee['nip'] ??
                                                'N/A'
                                                }}
                                            </p>
                                            <p class="text-gray-700">
                                                <span class="font-medium">Jabatan:</span> {{
                                                $selectedEmployee['jabatan'] ??
                                                'N/A' }}
                                            </p>
                                            @if(isset($selectedEmployee['pangkat']) && $selectedEmployee['pangkat'])
                                            <p class="text-gray-700">
                                                <span class="font-medium">Pangkat:</span> {{
                                                $selectedEmployee['pangkat'] }}
                                            </p>
                                            @endif
                                            @if(isset($selectedEmployee['eselon']) && $selectedEmployee['eselon'])
                                            <p class="text-gray-700">
                                                <span class="font-medium">Eselon:</span> {{ $selectedEmployee['eselon']
                                                }}
                                            </p>
                                            @endif
                                            @if(isset($selectedEmployee['golongan']) && $selectedEmployee['golongan'])
                                            <p class="text-gray-700">
                                                <span class="font-medium">Golongan:</span> {{
                                                $selectedEmployee['golongan']
                                                }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <button type="button" wire:click="$set('selectedEmployee', null)"
                                        class="ml-4 text-gray-400 hover:text-red-600 transition duration-150">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button> --}}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 3: Detail Perjalanan Dinas -->
                    <div
                        class="{{ $currentTab == 'detail' ? '' : 'hidden' }} bg-white rounded-xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-navy/5 to-blue-light/10 p-6 border-b-2 border-navy/20">
                            <h3 class="text-xl font-bold text-navy flex items-center gap-2">
                                <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Detail Perjalanan Dinas
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Nomor SPPD -->
                            <div>
                                <label for="sppd_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor SPPD <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="sppd_number" type="text" id="sppd_number"
                                    class="form-input @error('sppd_number') border-red-500 @enderror"
                                    placeholder="Masukkan nomor SPPD...">
                                @error('sppd_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Maksud Perjalanan -->
                            <div>
                                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                    Maksud/Tujuan Perjalanan Dinas <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="purpose" id="purpose" rows="4"
                                    class="form-input @error('purpose') border-red-500 @enderror"
                                    placeholder="Jelaskan maksud dan tujuan perjalanan dinas..."></textarea>
                                @error('purpose')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alat Angkut -->
                            <div>
                                <label for="transportation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alat Angkut/Transportasi <span class="text-red-500">*</span>
                                </label>
                                <div wire:ignore>
                                    <select wire:model="transportation" id="transportation"
                                        class="select2 form-input @error('transportation') border-red-500 @enderror"
                                        data-placeholder="-- Pilih Jenis Transportasi --">
                                        <option value="">-- Pilih Jenis Transportasi --</option>
                                        @foreach($alatAngkutOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('transportation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lokasi Perjalanan -->
                            <div>
                                <h4 class="text-base font-semibold text-navy mb-4 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lokasi Perjalanan Dinas
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="starting_place"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Lokasi Berangkat <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="starting_place" type="text" id="starting_place"
                                            class="form-input @error('starting_place') border-red-500 @enderror"
                                            placeholder="Contoh: Jakarta, Bandung, Surabaya">
                                        @error('starting_place')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="destination_places"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Lokasi Tujuan <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="destination_places" type="text" id="destination_places"
                                            class="form-input @error('destination_places') border-red-500 @enderror"
                                            placeholder="Contoh: Jakarta, Bandung, Surabaya">
                                        @error('destination_places')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Perjalanan -->
                            <div>
                                <h4 class="text-base font-semibold text-navy mb-4 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Lamanya Perjalanan Dinas
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="departure_date"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Berangkat <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="departure_date" type="date" id="departure_date"
                                            class="form-input @error('departure_date') border-red-500 @enderror">
                                        @error('departure_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="return_date" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Harus Kembali <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="return_date" type="date" id="return_date"
                                            class="form-input @error('return_date') border-red-500 @enderror">
                                        @error('return_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Biaya & Nomor SPPD -->
                    <div
                        class="{{ $currentTab == 'biaya' ? '' : 'hidden' }} bg-white rounded-xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-navy/5 to-blue-light/10 p-6 border-b-2 border-navy/20">
                            <h3 class="text-xl font-bold text-navy flex items-center gap-2">
                                <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tingkat Biaya Perjalanan Dinas
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <label for="cost_level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tingkat Biaya <span class="text-red-500">*</span>
                                </label>
                                <div wire:ignore>
                                    <select wire:model="cost_level" id="cost_level"
                                        class="select2 form-input @error('cost_level') border-red-500 @enderror"
                                        data-placeholder="-- Pilih Tingkat Biaya --">
                                        <option value="">-- Pilih Tingkat Biaya --</option>
                                        @foreach($tingkatOptions as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('cost_level')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end sm:mb-4 gap-2">
                                    <div class="flex-1 mb-2">
                                        <label for="kodeRekening" class="block text-sm font-medium text-gray-700">
                                            Sub Kegiatan & Kode Rekening
                                        </label>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ count($arrSubKegiatan) > 0 ? count($arrSubKegiatan) . ' Sub Kegiatan
                                            Ditemukan' : '' }}
                                        </div>
                                    </div>
                                    <div class="flex-none flex items-center gap-2">
                                        <div class="" wire:ignore>
                                            <select wire:model.live="selectedYear" id="selectedYear"
                                                class="select2 form-input w-[150px] @error('selectedYear') border-red-500 @enderror"
                                                data-placeholder="-- Pilih Tahun --">
                                                <option value="">-- Pilih Tahun --</option>
                                                @foreach($availableYears as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="" wire:ignore>
                                            <select wire:model.live="selectedMonth" id="selectedMonth"
                                                class="select2 form-input w-[150px] @error('selectedMonth') border-red-500 @enderror"
                                                data-placeholder="-- Pilih Bulan --">
                                                <option value="">-- Pilih Bulan --</option>
                                                @foreach($availableMonths as $month => $monthName)
                                                <option value="{{ $month }}">{{ $monthName }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @if($dataSuratPerintah->status == 'draft')
                                            <button type="button" wire:click="fetchKodeRekening"
                                                wire:loading.attr="disabled"
                                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                                                <span wire:loading.remove wire:target="fetchKodeRekening"
                                                    class="flex items-center">
                                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    <span>
                                                        Ambil Pagu
                                                    </span>
                                                </span>
                                                <span wire:loading wire:target="fetchKodeRekening">
                                                    <svg class="animate-spin h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </button>
                                        @else
                                            <button type="button" disabled
                                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                                                <span wire:loading.remove wire:target="fetchKodeRekening"
                                                    class="flex items-center">
                                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    <span>
                                                        Ambil Pagu
                                                    </span>
                                                </span>
                                                <span wire:loading wire:target="fetchKodeRekening">
                                                    <svg class="animate-spin h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2">
                                    <div class="flex-1" wire:ignore x-data="{
                                            arrSubKegiatan: @entangle('arrSubKegiatan'),
                                            subKegiatan: @entangle('subKegiatan')
                                        }" x-init="
                                            let select = $('#subKegiatan');
                                            select.select2({
                                                theme: 'default',
                                                width: '100%',
                                                placeholder: '-- Pilih Sub Kegiatan --',
                                                allowClear: true
                                            }).on('change', function() {
                                                subKegiatan = $(this).val();
                                                $wire.set('subKegiatan', subKegiatan);
                                            });

                                            $watch('arrSubKegiatan', value => {
                                                select.empty();
                                                select.append(new Option('-- Pilih Sub Kegiatan --', '', true, true));
                                                value.forEach(item => {
                                                    select.append(new Option(item.fullcode + ' - ' + item.name, item.fullcode));
                                                });
                                                select.trigger('change');
                                            });

                                            $watch('subKegiatan', value => {
                                                if (select.val() !== value) {
                                                    select.val(value).trigger('change.select2');
                                                }
                                            });
                                        ">
                                        <select id="subKegiatan"
                                            class="form-input @error('subKegiatan') border-red-500 @enderror">
                                            <option value="">-- Pilih Sub Kegiatan --</option>
                                            @foreach($arrSubKegiatan as $subKegiatan)
                                            <option value="{{ $subKegiatan['fullcode'] }}">
                                                {{ $subKegiatan['fullcode']. ' - ' . $subKegiatan['name'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if($isLoadedKodeRekening)
                                @if($subKegiatanData)
                                <div class="flex flex-col sm:flex-row gap-2 mt-4">
                                    <div class="flex-1" x-data="{
                                            arrKodeRekening: @entangle('arrKodeRekening'),
                                            kodeRekening: @entangle('kodeRekening')
                                        }" x-init="
                                            let select = $('#kodeRekening');
                                            select.select2({
                                                theme: 'default',
                                                width: '100%',
                                                placeholder: '-- Pilih Kode Rekening --',
                                                allowClear: true
                                            }).on('change', function() {
                                                kodeRekening = $(this).val();
                                                $wire.set('kodeRekening', kodeRekening);
                                            });

                                            $watch('arrKodeRekening', value => {
                                                select.empty();
                                                select.append(new Option('-- Pilih Kode Rekening --', '', true, true));
                                                if (value && value.length > 0) {
                                                    value.forEach(item => {
                                                        let pagu = new Intl.NumberFormat('id-ID').format(item.pagu_induk);
                                                        select.append(new Option(item.fullcode + ' - ' + item.name + ' (Rp. ' + pagu + ')', item.fullcode));
                                                    });
                                                }
                                                select.trigger('change');
                                            });

                                            $watch('kodeRekening', value => {
                                                if (select.val() !== value) {
                                                    select.val(value).trigger('change.select2');
                                                }
                                            });
                                        ">
                                        <select id="kodeRekening"
                                            class="form-input @error('kodeRekening') border-red-500 @enderror">
                                            <option value="">-- Pilih Kode Rekening --</option>
                                            @foreach($arrKodeRekening as $option)
                                            <option value="{{ $option['fullcode'] }}">
                                                {{ $option['fullcode']. ' - ' . $option['name'] }}
                                                (Rp. {{ number_format($option['pagu_induk'], 0, ',', '.') }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @error('kodeRekening')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @endif
                                @endif

                            </div>
                            <div>
                                @if($subKegiatan && $subKegiatanData)
                                <div class="mt-3 p-3 bg-indigo-50 border-l-4 border-indigo-400 rounded">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-400 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm text-indigo-700">
                                            <strong>
                                                {{ $isEdit ? 'Existing Sub Kegiatan' : 'Sub Kegiatan' }}
                                            </strong>
                                        </span>
                                    </div>
                                    <div class="mt-2 text-sm text-indigo-700">
                                        <p>
                                            <span class="font-medium">Kode:</span> {{ $subKegiatanData['fullcode'] }}
                                        </p>
                                        <p>
                                            <span class="font-medium">Uraian:</span>
                                            {{ $subKegiatanData['name'] }}
                                        </p>
                                        {{-- <p>
                                            <span class="font-medium">Pagu Induk:</span>
                                            Rp. {{ number_format($subKegiatanData['pagu_induk'] ?? 0, 0, ',', '.') }}
                                        </p> --}}
                                    </div>
                                </div>
                                @endif
                                @if($kodeRekening && $kodeRekeningData)
                                <div class="mt-3 p-3 bg-green-50 border-l-4 border-green-400 rounded">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm text-green-700">
                                            <strong>
                                                {{ $isEdit ? 'Existing Kode Rekening' : 'Kode Rekening' }}
                                            </strong>
                                        </span>
                                    </div>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p>
                                            <span class="font-medium">Kode:</span> {{ $kodeRekening }}
                                        </p>
                                        <p>
                                            <span class="font-medium">Uraian:</span>
                                            {{ $kodeRekeningData['name'] }}
                                        </p>
                                        <p>
                                            <span class="font-medium">Pagu Induk:</span>
                                            Rp. {{ number_format($kodeRekeningData['pagu_induk'] ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tab 5: Preview -->
                    @if($isEdit)
                    <div class="{{ $currentTab == 'preview' ? '' : 'hidden' }}">
                        <div class="">
                            <h3 class="text-xl font-bold text-navy flex items-center gap-2 p-4">
                                Preview Data SPPD
                            </h3>

                            <div class="mt-4 border border-navy rounded-xl p-4 pb-10 bg-white shadow-lg relative">
                                <div
                                    class="w-full h-full absolute top-0 left-0 bg-slate-400/20 flex items-center justify-center text-navy/50 text-4xl font-bold z-10 select-none">
                                    <span class="transform -rotate-45">PREVIEW SURAT PERINTAH PERJALANAN DINAS</span>
                                </div>
                                <h3 class="text-center m-0 underline text-2xl font-semibold">
                                    SURAT PERINTAH PERJALANAN DINAS (SPPD)
                                </h3>
                                <p class="uppercase text-center m-0 font-semibold text-xl">
                                    NOMOR : {{ $previewData['nomor_sppd'] }}
                                </p>

                                <div class="mt-4 overflow-x-auto">
                                    <table class="w-full">
                                        <tbody>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    1.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    Pejabat yang berwenang memberi perintah
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>
                                                        {{ $previewData['pejabat_jabatan'] }}
                                                    </p>
                                                    <p>
                                                        {{ $previewData['pejabat_name'] }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    2.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    Nama / NIP Pegawai yang melaksanakan perjalanan dinas
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>
                                                        {{ $previewData['pegawai_name'] }}
                                                    </p>
                                                    <p>
                                                        {{ $previewData['pegawai_nip'] }}
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    3.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>a. Pangkat dan Golongan</p>
                                                    <p>b. Jabatan / Instansi</p>
                                                    <p>c. Tingkat Biaya Perjalanan Dinas</p>
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>a. {{ $previewData['pegawai_pangkat'] }}</p>
                                                    <p>b. {{ $previewData['pegawai_jabatan'] }}</p>
                                                    <p>c. {{ $previewData['tingkat_biaya'] }}</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    4.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    Maksud Perjalanan Dinas
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p class="whitespace-preline">{{ $previewData['maksud_perjalanan']
                                                        }}</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    5.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    Alat Angkut yang dipergunakan
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>{{ $previewData['alat_angkutan'] }}</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    6.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>a. Tempat Berangkat</p>
                                                    <p>b. Tempat Tujuan</p>
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>a. {{ $previewData['tempat_berangkat'] }}</p>
                                                    <p>b. {{ $previewData['tempat_tujuan'] }}</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    7.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>a. Lamanya Perjalanan Dinas</p>
                                                    <p>b. Tanggal Berangkat</p>
                                                    <p>c. Tanggal Harus Kembali</p>
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>a. {{ $previewData['lama_perjalanan'] }} Hari</p>
                                                    <p>b. {{ $previewData['tanggal_berangkat'] }}</p>
                                                    <p>c. {{ $previewData['tanggal_kembali'] }}</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    8.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>Pembebanan Anggaran :</p>
                                                    <p>a. Instansi</p>
                                                    <p>b. Mata Anggaran</p>
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>a. {{ $previewData['pembebanan_instansi'] }}</p>
                                                    <p>b. {{ $previewData['kode_rekening'] }} - {{
                                                        $previewData['uraian_rekening'] }}</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                                    9.
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>Keterangan lain-lain</p>
                                                </td>
                                                <td class="min-w-[200px] border border-navy p-3 text-left">
                                                    <p>{{ $previewData['keterangan_lain'] }}</p>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-8 flex items-center justify-center sm:justify-end">
                                    <div class="flex flex-col items-center">
                                        <table>
                                            <tbody>
                                                <tr class="whitespace-nowrap">
                                                    <td>
                                                        Dikeluarkan di
                                                    </td>
                                                    <td class="px-3">: </td>
                                                    <td>
                                                        {{ $previewData['publication_place'] ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr class="whitespace-nowrap">
                                                    <td>
                                                        Pada tanggal
                                                    </td>
                                                    <td class="px-3">: </td>
                                                    <td>
                                                        {{ $previewData['publication_date'] ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="text-center">
                                            <div class="text-lg font-bold mt-4 whitespace-nowrap">
                                                @if($previewData['issued_nip'] != '1000')
                                                {{ $previewData['issued_jabatan_title'] }}
                                                @elseif($previewData['issued_nip'] == '1000')
                                                {{ $previewData['issued_jabatan'] }}
                                                @endif
                                            </div>
                                            <div class="h-[75px]">

                                            </div>
                                            <div class="whitespace-nowrap font-bold">
                                                {{ $previewData['issued_name'] }}
                                            </div>
                                            @if($previewData['issued_pangkat'] && $previewData['issued_golongan'])
                                            <div class="whitespace-nowrap">
                                                {{ $previewData['issued_pangkat'] }}
                                                ({{ $previewData['issued_golongan'] }})
                                            </div>
                                            @endif
                                            <div class="whitespace-nowrap">
                                                @if($previewData['issued_nip'] != '1000')
                                                NIP : {{ $previewData['issued_nip'] }}
                                                {{-- @else
                                                BUPATI KABUPATEN OGAN ILIR --}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div
                    class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between">
                    <div class="">
                        @if($dataSuratPerintah->status == 'approved' || $dataSuratPerintah->status == 'sent')
                        <span class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white {{ $dataSuratPerintah->status == 'approved' ? 'bg-green-400' : 'bg-orange-400' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $dataSuratPerintah->status == 'approved' ? 'focus:ring-green-500' : 'focus:ring-orange-500' }} disabled:opacity-50 transition duration-150">
                            @if($dataSuratPerintah->status == 'approved')
                            <x-heroicon-o-check-badge class="h-5 w-5 mr-2" />
                            @elseif($dataSuratPerintah->status == 'sent')
                            <x-heroicon-m-exclamation-triangle class="h-5 w-5 mr-2" />
                            @endif
                            {{ $dataSuratPerintah->status == 'approved' ? 'Surat telah Ditandatangi' : 'Menunggu Ditandatangani' }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.surat-perintah.sppd', ['id' => $suratPerintahId]) }}"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                            Kembali
                        </a>
                        @if($isEdit)
                        {{-- check status data perintah --}}
                        @if($dataSuratPerintah->status == 'draft')
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                            <span wire:loading.remove class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $isEdit ? 'Update SPPD' : 'Simpan SPPD' }}
                            </span>
                            <span wire:loading wire:target="submitForm">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        @else
                        <button type="button"
                            disabled
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                            <span wire:loading.remove class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $isEdit ? 'Update SPPD' : 'Simpan SPPD' }}
                            </span>
                        </button>
                        @endif
                        {{-- check status data perintah --}}
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Wait for all scripts to load
    window.addEventListener('load', function() {
        // Give extra time for CDN scripts to initialize
        setTimeout(function() {
            // Check if jQuery and Select2 are loaded
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded!');
                return;
            }

            if (typeof jQuery.fn.select2 === 'undefined') {
                console.error('Select2 is not loaded!');
                return;
            }

            console.log('jQuery and Select2 loaded successfully!');
            initializeSelect2();
        }, 100);
    });

    // Also initialize when Livewire is ready
    document.addEventListener('livewire:navigated', function() {
        // console.log('Livewire navigated, reinitializing Select2...');
        initializeSelect2();
    });

    function initializeSelect2() {
        const $ = jQuery; // Ensure we have jQuery

        // Destroy existing instances first
        if ($('#instanceGiverSelect').hasClass('select2-hidden-accessible')) {
            $('#instanceGiverSelect').select2('destroy');
        }
        if ($('#instanceSelect').hasClass('select2-hidden-accessible')) {
            $('#instanceSelect').select2('destroy');
        }
        if ($('#transportation').hasClass('select2-hidden-accessible')) {
            $('#transportation').select2('destroy');
        }
        if ($('#cost_level').hasClass('select2-hidden-accessible')) {
            $('#cost_level').select2('destroy');
        }
        if ($('#selectedYear').hasClass('select2-hidden-accessible')) {
            $('#selectedYear').select2('destroy');
        }
        if ($('#selectedMonth').hasClass('select2-hidden-accessible')) {
            $('#selectedMonth').select2('destroy');
        }

        // Initialize Instance Giver Select (for commanding officer)
        try {
            $('#instanceGiverSelect').select2({
                theme: 'default',
                width: '100%',
                placeholder: '-- Pilih Instansi --',
                allowClear: true
            }).on('change', function(e) {
                const value = $(this).val();
                console.log('Instance Giver changed to:', value);
                @this.set('selectedInstanceGiver', value);
                // if(value != '0' || value != 0){
                //     $('#instanceSelect').prop('disabled', true);
                // }
            });
            // console.log('Instance Giver Select2 initialized');
        } catch (error) {
            console.error('Error initializing instanceGiverSelect:', error);
        }

        // Initialize Instance Select
        try {
            $('#instanceSelect').select2({
                theme: 'default',
                width: '100%',
                placeholder: '-- Pilih Instansi --',
                allowClear: true
            }).on('change', function(e) {
                const value = $(this).val();
                console.log('Instance changed to:', value);
                @this.set('selectedInstance', value);
            });
            // console.log('Instance Select2 initialized');
        } catch (error) {
            console.error('Error initializing instanceSelect:', error);
        }
        @if($isDisabledInstances == true && $selectedInstanceGiver != '0')

            // $('#instanceSelect').prop('disabled', true);
        @endif

        // Initialize Transportation Select
        try {
            $('#transportation').select2({
                theme: 'default',
                width: '100%',
                placeholder: '-- Pilih Jenis Transportasi --',
                allowClear: true,
                minimumResultsForSearch: -1
            }).on('change', function(e) {
                const value = $(this).val();
                console.log('Transportation changed to:', value);
                @this.set('transportation', value);
            });
            // console.log('Transportation Select2 initialized');
        } catch (error) {
            console.error('Error initializing transportation:', error);
        }

        // Initialize Cost Level Select
        try {
            $('#cost_level').select2({
                theme: 'default',
                width: '100%',
                placeholder: '-- Pilih Tingkat Biaya --',
                allowClear: true,
                minimumResultsForSearch: -1
            }).on('change', function(e) {
                const value = $(this).val();
                // console.log('Cost level changed to:', value);
                @this.set('cost_level', value);
            });
            // console.log('Cost Level Select2 initialized');
        } catch (error) {
            console.error('Error initializing cost_level:', error);
        }

        // Initialize Selected Year Select
        try {
            $('#selectedYear').select2({
                theme: 'default',
                width: '100%',
                placeholder: '-- Pilih Tahun --',
                allowClear: true,
                minimumResultsForSearch: -1
            }).on('change', function(e) {
                const value = $(this).val();
                // console.log('Selected Year changed to:', value);
                @this.set('selectedYear', value);
            });
            // console.log('Selected Year Select2 initialized');
        } catch (error) {
            console.error('Error initializing selectedYear:', error);
        }

        // Initialize Selected Month Select
        try {
            $('#selectedMonth').select2({
                theme: 'default',
                width: '100%',
                placeholder: '-- Pilih Bulan --',
                allowClear: true,
                minimumResultsForSearch: -1
            }).on('change', function(e) {
                const value = $(this).val();
                // console.log('Selected Month changed to:', value);
                @this.set('selectedMonth', value);
            });
            // console.log('Selected Month Select2 initialized');
        } catch (error) {
            console.error('Error initializing selectedMonth:', error);
        }

        // Set initial values if editing
        @if($isEdit)

            // setTimeout(() => {
            //     $('#instanceGiverSelect').val('{{ $selectedInstanceGiver ?? "" }}').trigger('change.select2');
            //     $('#instanceSelect').val('{{ $selectedInstance ?? "" }}').trigger('change.select2');
            //     $('#transportation').val('{{ $transportation ?? "" }}').trigger('change.select2');
            //     $('#cost_level').val('{{ $cost_level ?? "" }}').trigger('change.select2');
            //     $('#selectedYear').val('{{ $selectedYear ?? "" }}').trigger('change.select2');
            //     $('#selectedMonth').val('{{ $selectedMonth ?? "" }}').trigger('change.select2');
            //     // console.log('Initial values set for edit mode');
            // }, 500);
        @endif
    }

    // Listen for Livewire events
    window.addEventListener('select2:refresh', () => {
        setTimeout(() => {
            initializeSelect2();
            console.log('Select2 refresh event received');
        }, 100);
    });
</script>
@endpush
