<div class="min-h-screen bg-light py-8" x-data="{ currentTab: '{{ $currentTab }}' }">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    {{ $isEdit ? 'Edit Surat Perintah Tugas' : 'Buat Surat Perintah Tugas Baru' }}
                </h2>
                <p class="mt-2 text-sm text-muted">Formulir Surat Perintah Tugas</p>
            </div>
            <a href="{{ route('admin.surat-perintah.index') }}" class="btn-secondary">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="hidden xl:block">
                    Daftar Surat Perintah Tugas
                </span>
                <span class="xl:hidden block">
                    Daftar SPT
                </span>
            </a>
        </div>

        <div class="card">

            @if ($isEdit)
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 bg-gradient-to-r from-primary/5 to-secondary/10">
                    <div class="overflow-x-auto scrollbar-hide">
                        <nav class="tab-nav" aria-label="Tabs">
                            <!-- Tab 1: Input -->
                            <button type="button" wire:click="$set('currentTab', 'input')"
                                class="{{ $currentTab == 'input' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                                <x-heroicon-o-briefcase class="w-5 h-5 currentColor" />
                                <span class="hidden sm:inline">Form</span>
                                <span class="sm:hidden">Form</span>
                            </button>

                            <!-- Tab 2: SPPD -->
                            <a href="{{ route('admin.surat-perintah.sppd', ['id' => $dataId]) }}"
                                class="{{ $currentTab == 'sppd' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                                <x-heroicon-o-users class="w-5 h-5 currentColor" />
                                <span class="hidden sm:inline">SPPD</span>
                                <span class="sm:hidden">SPPD</span>
                            </a>

                            <!-- Tab 3: Preview -->
                            <a href="{{ route('admin.surat-perintah.preview', ['id' => $dataId]) }}"
                                class="{{ $currentTab == 'preview' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                                <x-heroicon-o-photo class="w-5 h-5 currentColor" />
                                <span class="hidden sm:inline">Preview</span>
                                <span class="sm:hidden">Preview</span>
                            </a>

                            <!-- Tab 4: Logs -->
                            <a href="{{ route('admin.surat-perintah.logs', ['id' => $dataId]) }}"
                                class="{{ $currentTab == 'logs' ? 'tab-button-active' : 'tab-button' }} min-w-fit flex-grow justify-center">
                                <x-heroicon-m-queue-list class="w-5 h-5 currentColor" />
                                <span class="hidden sm:inline">Log</span>
                                <span class="sm:hidden">Log</span>
                            </a>
                        </nav>
                    </div>
                </div>
            @endif

            <!-- Tab 1: Input -->
            <div class="{{ $currentTab == 'input' ? '' : 'hidden' }}">
                <div class="card-header">
                    <h3
                        class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent mb-2 flex items-center">
                        <x-heroicon-o-briefcase class="w-5 h-5 text-primary" />
                        <span class="ml-2">Form Surat Perintah Tugas</span>
                    </h3>
                    <p class="text-sm text-muted">
                        Lengkapi formulir Surat Perintah Tugas dengan data yang akurat dan lengkap.
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <div class="space-y-6">
                        <!-- Pilih Instansi untuk API -->
                        <div class="">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pejabat Pemberi Perintah <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <div class="flex-1" wire:ignore>
                                    <select wire:model="selectedInstanceGiver" class="select2 form-input"
                                        @if ($isViewOnly) disabled @endif
                                        data-placeholder="-- Pejabat Pemberi Perintah --" id="instanceGiverSelect">
                                        <option value="">-- Pejabat Pemberi Perintah --</option>
                                        @foreach ($instances as $inst)
                                            <option value="{{ $inst['id'] }}">KEPALA {{ $inst['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($selectedInstanceGiver != 0 && $isViewOnly == false)
                                    <button type="button" wire:click="fetchSemestaOfficers"
                                        wire:loading.attr="disabled"
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
                            @error('commanding_officer')
                                <div class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Daftar Pejabat dari API -->
                        {{-- @if (count($rawSemestaOfficers) > 0)
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
                                    <button type="button" wire:click="toggleShowOfficers"
                                        class="ml-2 px-3 py-1 bg-gray-200 rounded-lg text-sm">
                                        {{ $showOfficers ? 'Sembunyikan Pejabat' : 'Tampilkan Pejabat' }}
                                    </button>
                                </div>

                                @if ($showOfficers)
                                    <div class="max-h-60 overflow-y-auto space-y-2">
                                        @forelse ($semestaOfficers as $officer)
                                            <div wire:click="selectOfficer({{ json_encode($officer) }})"
                                                class="p-3 bg-white border border-gray-200 rounded-lg hover:bg-navy hover:text-white cursor-pointer transition duration-150">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-medium">
                                                        {{ $officer['nama_lengkap'] ?? 'N/A' }}
                                                    </p>
                                                    @if ($officer['kepala_skpd'] == 'Y')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Kepala OPD / Bagian
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm opacity-75">
                                                    NIP: {{ $officer['nip'] ?? 'N/A' }} |
                                                    {{ $officer['jabatan'] ?? 'N/A' }}
                                                </p>
                                            </div>
                                        @empty
                                            <div
                                                class="p-3 bg-white border border-gray-200 rounded-lg cursor-pointer transition duration-150">
                                                <p class="text-sm text-gray-500">Tidak ada pejabat yang ditemukan.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                        @endif --}}

                        <!-- Informasi Pejabat yang Dipilih -->
                        @if ($selectedOfficer)
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="text-sm font-semibold text-green-800">Pemberi Perintah</h4>
                                        </div>
                                        <div class="space-y-1 text-sm">
                                            <p class="text-gray-900">
                                                <span class="font-medium">Nama:</span>
                                                <span
                                                    class="text-navy font-semibold">{{ $selectedOfficer['nama_lengkap'] ?? 'N/A' }}</span>
                                                @if (isset($selectedOfficer['kepala_skpd']) && $selectedOfficer['kepala_skpd'] == 'Y')
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Kepala OPD
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="text-gray-700">
                                                <span class="font-medium">NIP:</span>
                                                {{ $selectedOfficer['nip'] ?? 'N/A' }}
                                            </p>
                                            <p class="text-gray-700">
                                                <span class="font-medium">Jabatan:</span>
                                                {{ $selectedOfficer['jabatan'] ?? 'N/A' }}
                                            </p>
                                            @if (isset($selectedOfficer['pangkat']) && $selectedOfficer['pangkat'])
                                                <p class="text-gray-700">
                                                    <span class="font-medium">Pangkat:</span>
                                                    {{ $selectedOfficer['pangkat'] }}
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

                    <!-- Include the main form fields -->

                    <!-- Klasifikasi Surat -->
                    <div class="mb-6 {{ $selectedOfficer ? '' : 'hidden' }}">
                        <label for="klasifikasi_surat" class="block text-sm font-medium text-gray-700 mb-2">
                            Klasifikasi Surat <span class="text-red-500">*</span>
                        </label>
                        <div wire:ignore>
                            <select wire:model="dataSuratPerintah.klasifikasi_surat_id" id="klasifikasiSuratSelect"
                                @if ($isViewOnly) disabled @endif
                                class="select2 form-input @error('dataSuratPerintah.klasifikasi_surat_id') border-red-500 @enderror"
                                data-placeholder="-- Pilih Klasifikasi Surat --">
                                <option value="">-- Pilih Klasifikasi Surat --</option>
                                @foreach ($klasifikasiOptions as $option)
                                    <optgroup label="{{ $option['kode'] }} - {{ $option['klasifikasi'] }}"
                                        value="{{ $option['id'] }}">
                                        @foreach ($option['children'] as $child)
                                            <option value="{{ $child['id'] }}">
                                                {{ $child['kode'] }} - {{ $child['klasifikasi'] }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        @error('dataSuratPerintah.klasifikasi_surat_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div
                        class="space-y-6 {{ $selectedOfficer && $dataSuratPerintah['klasifikasi_surat_id'] ? '' : 'hidden' }}">

                        <!-- Nomor Surat Perintah Tugas -->
                        <div>
                            <label for="nomor_surat" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Surat Perintah Tugas <span class="text-red-500">*</span>
                            </label>
                            <div class="flex relative form-input !p-0 gap-2 items-center">
                                <div class="whitespace-nowrap bg-slate-200 text-gray-700 p-3 rounded-l-lg">
                                    {{ $dataSuratPerintah['prefix_nomor_surat'] ?? '-' }}
                                </div>
                                <input wire:model="dataSuratPerintah.nomor_surat" type="text" id="nomor_surat"
                                    @if ($isViewOnly) disabled @endif
                                    class="w-full h-full p-3 pl-0 ring-0 outline-0 border-0 focus:ring-0 focus:outline-0 !bg-transparent @error('dataSuratPerintah.nomor_surat') border-red-500 @enderror"
                                    placeholder="Masukkan nomor Surat Perintah Tugas...">
                            </div>
                            @error('dataSuratPerintah.nomor_surat')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dasar -->
                        <div>
                            <label for="dasar" class="block text-sm font-medium text-gray-700 mb-2">
                                Dasar <span class="text-red-500">*</span>
                            </label>
                            <div wire:ignore>
                                <div id="surat-perintah-dasar" style="height: 200px;"
                                    @if ($isViewOnly) disabled @endif>
                                </div>
                            </div>
                            @error('dataSuratPerintah.dasar')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tujuan -->
                        <div>
                            <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                                Maksud Perjalanan Dinas <span class="text-red-500">*</span>
                            </label>
                            <div wire:ignore>
                                <div id="surat-perintah-tujuan" style="height: 200px;"></div>
                            </div>
                            @error('dataSuratPerintah.tujuan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Alat Angkut -->
                        <div>
                            <label for="transportation" class="block text-sm font-medium text-gray-700 mb-2">
                                Alat Angkut/Transportasi <span class="text-red-500">*</span>
                            </label>
                            <div wire:ignore>
                                <select wire:model="dataSuratPerintah.alat_angkutan" id="transportation"
                                    @if ($isViewOnly) disabled @endif
                                    class="select2 form-input @error('dataSuratPerintah.alat_angkutan') border-red-500 @enderror"
                                    data-placeholder="-- Pilih Jenis Transportasi --">
                                    <option value="">-- Pilih Jenis Transportasi --</option>
                                    @foreach ($alatAngkutOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('dataSuratPerintah.alat_angkutan')
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
                                Berangkat
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-1 md:col-span-2">
                                    <label for="starting_place" class="block text-sm font-medium text-gray-700 mb-2">
                                        Lokasi Berangkat <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="dataSuratPerintah.tempat_berangkat" type="text"
                                        id="starting_place" @if ($isViewOnly) disabled @endif
                                        class="form-input @error('dataSuratPerintah.tempat_berangkat') border-red-500 @enderror"
                                        placeholder="Contoh: Jakarta, Bandung, Surabaya">
                                    @error('dataSuratPerintah.tempat_berangkat')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <h4 class="text-base font-semibold text-navy flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Tujuan
                                    </h4>
                                </div>

                                <!-- Provinsi -->
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                                        Provinsi Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <div wire:ignore>
                                        <select wire:model="dataSuratPerintah.province_id" id="province"
                                            @if ($isViewOnly) disabled @endif
                                            class="select2 form-input @error('dataSuratPerintah.province_id') border-red-500 @enderror"
                                            data-placeholder="-- Pilih Provinsi --">
                                            <option value="">-- Pilih Provinsi --</option>
                                            @foreach ($provincesOptions as $option)
                                                <option value="{{ $option['id'] }}">
                                                    {{ $option['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('dataSuratPerintah.province_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kabupaten/Kota -->
                                <div>
                                    <label for="regency" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kabupaten/Kota Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <div wire:ignore.self>
                                        <select wire:model="dataSuratPerintah.regency_id" id="regency"
                                            @if ($isViewOnly) disabled @endif
                                            class="select2 form-input @error('dataSuratPerintah.regency_id') border-red-500 @enderror"
                                            data-placeholder="-- Pilih Kabupaten/Kota --">
                                            <option value="">-- Pilih Kabupaten/Kota --</option>
                                            @foreach ($regenciesOptions as $option)
                                                <option value="{{ $option['id'] }}">
                                                    {{ $option['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('dataSuratPerintah.regency_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label for="destination_places"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Lokasi Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    {{-- <input wire:model="dataSuratPerintah.tempat_tujuan" type="text"
                                        id="destination_places" @if ($isViewOnly) disabled @endif
                                        class="form-input @error('dataSuratPerintah.tempat_tujuan') border-red-500 @enderror"
                                        placeholder="Contoh: Jakarta, Bandung, Surabaya"> --}}
                                    <textarea wire:model="dataSuratPerintah.tempat_tujuan" id="destination_places"
                                        @if ($isViewOnly) disabled @endif
                                        class="form-textarea @error('dataSuratPerintah.tempat_tujuan') border-red-500 @enderror"
                                        placeholder="Contoh: Kantor A, Lembaga B, Instansi C"></textarea>
                                    @error('dataSuratPerintah.tempat_tujuan')
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
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div class="md:col-span-2">
                                    <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Berangkat <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live="dataSuratPerintah.tanggal_berangkat" type="date"
                                        id="departure_date" @if ($isViewOnly) disabled @endif
                                        class="form-input @error('dataSuratPerintah.tanggal_berangkat') border-red-500 @enderror">
                                    @error('dataSuratPerintah.tanggal_berangkat')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="lama_perjalanan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Lama Perjalanan (Hari) <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live="dataSuratPerintah.lama_perjalanan" type="number"
                                        min="1" max="10" id="lama_perjalanan" placeholder="Lama Perjalanan (Hari)"
                                        @if ($isViewOnly) disabled @endif
                                        class="form-input @error('dataSuratPerintah.lama_perjalanan') border-red-500 @enderror">
                                    @error('dataSuratPerintah.lama_perjalanan')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="return_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Kembali <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="dataSuratPerintah.tanggal_pulang" type="date"
                                        id="return_date" disabled
                                        class="form-input @error('dataSuratPerintah.tanggal_pulang') border-red-500 @enderror">
                                    @error('dataSuratPerintah.tanggal_pulang')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Publication place & date -->
                        <div>
                            <h4 class="text-base font-semibold text-navy mb-4 flex items-center gap-2">
                                <x-heroicon-o-document-check class="w-5 h-5 text-gray-500" />
                                Tempat & Tanggal Dikeluarkan Surat Perintah Tugas
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="publication_place"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Tempat Dikeluarkan Surat Perintah Tugas <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="dataSuratPerintah.publication_place" type="text"
                                        id="publication_place" placeholder="Contoh: Jakarta"
                                        @if ($isViewOnly) disabled @endif
                                        class="form-input @error('dataSuratPerintah.publication_place') border-red-500 @enderror">
                                    @error('dataSuratPerintah.publication_place')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="publication_date"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Dikeluarkan Surat Perintah Tugas <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="dataSuratPerintah.publication_date" type="date"
                                        id="publication_date" @if ($isViewOnly) disabled @endif
                                        class="form-input @error('dataSuratPerintah.publication_date') border-red-500 @enderror">
                                    @error('dataSuratPerintah.publication_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>


                                <!-- Daftar Pejabat dari API -->
                                @if ($selectedSigner)
                                    <div class="md:col-span-2 hidden">
                                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <svg class="h-5 w-5 text-green-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <h4 class="text-sm font-semibold text-green-800">
                                                            Penandatangan Surat Perintah Tugas
                                                        </h4>
                                                    </div>
                                                    <div class="space-y-1 text-sm">
                                                        <p class="text-gray-900">
                                                            <span class="font-medium">Nama:</span>
                                                            <span
                                                                class="text-navy font-semibold">{{ $selectedSigner['nama_lengkap'] ?? 'N/A' }}</span>
                                                            @if (isset($selectedSigner['kepala_skpd']) && $selectedSigner['kepala_skpd'] == 'Y')
                                                                <span
                                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    Kepala OPD
                                                                </span>
                                                            @endif
                                                        </p>
                                                        <p class="text-gray-700">
                                                            <span class="font-medium">NIP:</span>
                                                            {{ $selectedSigner['nip'] ?? 'N/A' }}
                                                        </p>
                                                        <p class="text-gray-700">
                                                            <span class="font-medium">Jabatan:</span>
                                                            {{ $selectedSigner['jabatan'] ?? 'N/A' }}
                                                        </p>
                                                        @if (isset($selectedSigner['pangkat']) && $selectedSigner['pangkat'])
                                                            <p class="text-gray-700">
                                                                <span class="font-medium">Pangkat:</span>
                                                                {{ $selectedSigner['pangkat'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (!$isEdit)
                                    <div class="flex items-center justify-end md:col-span-2">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer"
                                                wire:model.live="isWithSppd" value="1"
                                                @if ($isViewOnly) disabled @endif>
                                            <div
                                                class="relative w-14 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-soft dark:peer-focus:ring-brand-soft rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-buffer after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-7 after:transition-all peer-checked:bg-primary">
                                            </div>
                                            <span class="select-none ms-3 text-xl font-bold text-heading">
                                                Sertakan SPPD
                                            </span>
                                        </label>
                                    </div>
                                @endif

                            </div>
                        </div>


                        <div class="py-6 flex flex-col sm:flex-row gap-4 sm:gap-3 items-center justify-between">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.surat-perintah.index') }}" class="btn-secondary">
                                    <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
                                    Kembali
                                </a>

                                {{-- status --}}
                                @if ($isEdit)
                                    @if ($dataSuratPerintah['status'] == 'draft')
                                        <div class="btn-primary">
                                            <x-heroicon-o-hand-raised class="w-4 h-4 mr-1" />
                                            Draft
                                        </div>
                                    @elseif($dataSuratPerintah['status'] == 'sent')
                                        <div class="btn-warning">
                                            <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                            Menunggu Tanda Tangan
                                        </div>
                                    @elseif($dataSuratPerintah['status'] == 'approved')
                                        <div class="btn-success">
                                            <x-heroicon-o-finger-print class="w-4 h-4 mr-1" />
                                            Ditandatangani
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div
                                class="flex items-center justify-center sm:justify-end flex-wrap gap-3 whitespace-nowrap">
                                @if (!$isEdit)
                                    @if ($isWithSppd)
                                        <button type="button" wire:click="confirmSubmitForm('with_sppd')"
                                            wire:loading.attr="disabled" class="btn-primary">
                                            <span wire:loading.remove wire:target="submitForm('with_sppd')">
                                                Buat Surat Perintah Tugas Dengan SPPD
                                            </span>
                                            <span wire:loading wire:target="submitForm('with_sppd')">
                                                <svg class="animate-spin h-5 w-5 text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    @else
                                        <button type="button" wire:click="confirmSubmitForm()" wire:loading.attr="disabled"
                                            class="btn-primary">
                                            <span wire:loading.remove wire:target="submitForm()">
                                                Buat Surat Perintah Tugas
                                            </span>
                                            <span wire:loading wire:target="submitForm()">
                                                <svg class="animate-spin h-5 w-5 text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    @endif
                                @else
                                    {{-- <button type="button" wire:click="confirmSent()" wire:loading.attr="disabled"
                                        @if ($isViewOnly) disabled @endif class="btn-success">
                                        <span wire:loading.remove wire:target="confirmSent()"
                                            class="flex items-center gap-1">
                                            <x-heroicon-o-paper-airplane class="w-4 h-4 mr-1" />
                                            Kirim untuk Ditandatangani
                                        </span>
                                        <span wire:loading wire:target="confirmSent()">
                                            <svg class="animate-spin h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </span>
                                    </button> --}}

                                    <button type="button" wire:click="submitForm('edit')"
                                        wire:loading.attr="disabled"
                                        @if ($isViewOnly) disabled @endif class="btn-primary">
                                        <span wire:loading.remove wire:target="submitForm('edit')"
                                            class="flex items-center gap-1">
                                            <x-heroicon-m-document-check class="w-4 h-4 mr-1" />
                                            Simpan Perubahan
                                        </span>
                                        <span wire:loading wire:target="submitForm('edit')">
                                            <svg class="animate-spin h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    @if ($isConverting)
        <div
            class="fixed z-[200] bg-black w-screen h-screen top-0 left-0 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                <svg class="animate-spin h-8 w-8 text-navy" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <div class="text-lg font-medium text-navy">
                    Sedang memproses dokumen, mohon tunggu...
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        Livewire.on('initiateConvert', () => {
            $wire.set('isConverting', true);
            setTimeout(() => {
                $wire.call('sent');
            }, 500);
        });

        Livewire.on('finishConverting', () => {
            $wire.set('isConverting', false);
            $wire.call('loadSuratPerintah', {{ $dataSuratPerintah['id'] }});
            // setInterval(() => {
            //     $wire.$refresh()
            // }, 500)
        });
    </script>
@endscript

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

                // console.log('jQuery and Select2 loaded successfully!');
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
            if ($('#klasifikasiSuratSelect').hasClass('select2-hidden-accessible')) {
                $('#klasifikasiSuratSelect').select2('destroy');
            }

            // Initialize Instance Giver Select (for commanding officer)
            try {
                $('#instanceGiverSelect').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: '-- Pilih Perangkat Daerah --',
                    allowClear: true
                }).on('change', function(e) {
                    const value = $(this).val();
                    // console.log('Instance Giver changed to:', value);
                    @this.set('selectedInstanceGiver', value);
                    // if(value != '0' || value != 0){
                    //     $('#instanceSelect').prop('disabled', true);
                    // }
                });

                // Set initial value for edit mode
                @if ($isEdit && isset($selectedInstanceGiver))

                    setTimeout(() => {
                        $('#instanceGiverSelect').val('{{ $selectedInstanceGiver }}').trigger('change.select2');
                        // console.log('Instance Giver set to: {{ $selectedInstanceGiver }}');
                    }, 200);
                @endif

                // console.log('Instance Giver Select2 initialized');
            } catch (error) {
                console.error('Error initializing instanceGiverSelect:', error);
            }

            // Initialize Instance Select
            try {
                $('#instanceSelect').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: '-- Pilih Perangkat Daerah --',
                    allowClear: true
                }).on('change', function(e) {
                    const value = $(this).val();
                    // console.log('Instance changed to:', value);
                    @this.set('selectedInstance', value);
                });
                // console.log('Instance Select2 initialized');
            } catch (error) {
                console.error('Error initializing instanceSelect:', error);
            }
            @if ($isDisabledInstances == true && $selectedInstanceGiver != '0')

                // $('#instanceSelect').prop('disabled', true);
            @endif

            // Initialize Klasifikasi Surat Select
            try {
                $('#klasifikasiSuratSelect').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: '-- Pilih Klasifikasi Surat --',
                    allowClear: true,
                    // minimumResultsForSearch: -1,
                    tags: true
                }).on('change', function(e) {
                    const value = $(this).val();
                    // console.log('Klasifikasi Surat changed to:', value);
                    @this.set('dataSuratPerintah.klasifikasi_surat_id', value);
                });

                // Set initial value for edit mode
                @if ($isEdit && isset($dataSuratPerintah['klasifikasi_surat_id']))

                    setTimeout(() => {
                        $('#klasifikasi_surat').val('{{ $dataSuratPerintah['klasifikasi_surat_id'] }}').trigger(
                            'change.select2');
                        console.log('Klasifikasi Surat set to: {{ $dataSuratPerintah['klasifikasi_surat_id'] }}');
                    }, 200);
                @endif

                // console.log('Klasifikasi Surat Select2 initialized');
            } catch (error) {
                console.error('Error initializing klasifikasi_surat:', error);
            }

            // Initialize Transportation Select
            try {
                $('#transportation').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: '-- Pilih Jenis Transportasi --',
                    allowClear: true,
                    // minimumResultsForSearch: -1,
                    tags: true
                }).on('change', function(e) {
                    const value = $(this).val();
                    // console.log('Transportation changed to:', value);
                    @this.set('dataSuratPerintah.alat_angkutan', value);
                });

                // Set initial value for edit mode
                @if ($isEdit && isset($dataSuratPerintah['alat_angkutan']))

                    setTimeout(() => {
                        $('#transportation').val('{{ $dataSuratPerintah['alat_angkutan'] }}').trigger(
                            'change.select2');
                        // console.log('Transportation set to: {{ $dataSuratPerintah['alat_angkutan'] }}');
                    }, 200);
                @endif

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

            // Initialize Province Select
            try {
                $('#province').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: '-- Pilih Provinsi --',
                    allowClear: true,
                    // minimumResultsForSearch: -1
                }).on('change', function(e) {
                    const value = $(this).val();
                    // console.log('Province changed to:', value);
                    @this.set('dataSuratPerintah.province_id', value);
                });

                // Set initial value for edit mode
                @if ($isEdit && isset($dataSuratPerintah['province_id']))

                    setTimeout(() => {
                        $('#province').val('{{ $dataSuratPerintah['province_id'] }}').trigger('change.select2');
                        // console.log('Province set to: {{ $dataSuratPerintah['province_id'] }}');
                    }, 200);
                @endif

                // console.log('Province Select2 initialized');
            } catch (error) {
                console.error('Error initializing province:', error);
            }

            // Initialize Regency Select
            try {
                $('#regency').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: '-- Pilih Kabupaten/Kota --',
                    allowClear: true,
                    // minimumResultsForSearch: -1
                }).on('change', function(e) {
                    const value = $(this).val();
                    // console.log('Regency changed to:', value);
                    @this.set('dataSuratPerintah.regency_id', value);
                });

                // Set initial value for edit mode
                @if ($isEdit && isset($dataSuratPerintah['regency_id']))

                    setTimeout(() => {
                        $('#regency').val('{{ $dataSuratPerintah['regency_id'] }}').trigger('change.select2');
                        // console.log('Regency set to: {{ $dataSuratPerintah['regency_id'] }}');
                    }, 200);
                @endif

                // console.log('Regency Select2 initialized');
            } catch (error) {
                console.error('Error initializing regency:', error);
            }

            // Set initial values if editing
            @if ($isEdit)

                // setTimeout(() => {
                //     $('#instanceGiverSelect').val('{{ $selectedInstanceGiver ?? '' }}').trigger('change.select2');
                //     $('#instanceSelect').val('{{ $selectedInstance ?? '' }}').trigger('change.select2');
                //     $('#transportation').val('{{ $transportation ?? '' }}').trigger('change.select2');
                //     $('#cost_level').val('{{ $cost_level ?? '' }}').trigger('change.select2');
                //     $('#selectedYear').val('{{ $selectedYear ?? '' }}').trigger('change.select2');
                //     $('#selectedMonth').val('{{ $selectedMonth ?? '' }}').trigger('change.select2');
                //     // console.log('Initial values set for edit mode');
                // }, 500);
            @endif
        }

        // Listen for Livewire events
        window.addEventListener('select2:refresh', () => {
            setTimeout(() => {
                initializeSelect2();
                // console.log('Select2 refresh event received');
            }, 100);
        });

        // Dispatch a custom event to trigger Select2 refresh
        Livewire.on('select2:refresh', () => {
            setTimeout(() => {
                initializeSelect2();
                // console.log('Livewire select2:refresh event triggered');
            }, 100);
        });
    </script>

    <!-- Quill Editor -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            let quill;

            const initQuill = () => {
                if (quill) {
                    quill = null;
                }

                quill = new Quill('#surat-perintah-dasar', {
                    readOnly: {{ $isViewOnly ? 'true' : 'false' }},
                    theme: 'snow',
                    placeholder: {{ $isViewOnly ? '``' : '`Tuliskan konten dasar...`' }},
                    modules: {
                        toolbar: [
                            [{
                                'list': 'ordered'
                            }],
                            ['clean']
                        ]
                    }
                });

                // Set initial content
                const content = @js($dataSuratPerintah['dasar'] ?? '');
                if (content) {
                    quill.root.innerHTML = content;
                }

                // Sync with Livewire
                quill.on('text-change', () => {
                    @this.set('dataSuratPerintah.dasar', quill.root.innerHTML);
                });

            };

            // Initialize on page load
            initQuill();

            // Reinitialize when Livewire navigates
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                if (el.id === 'surat-perintah-dasar') {
                    initQuill();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:init', () => {
            let quill;

            const initQuill = () => {
                if (quill) {
                    quill = null;
                }

                quill = new Quill('#surat-perintah-tujuan', {
                    readOnly: {{ $isViewOnly ? 'true' : 'false' }},
                    theme: 'snow',
                    placeholder: {{ $isViewOnly ? '``' : '`Tuliskan konten maksud perjalanan dinas...`' }},
                    modules: {
                        toolbar: [
                            // [{ 'list': 'ordered'}],
                            ['clean']
                        ]
                    }
                });

                // Set initial content
                const content = @js($dataSuratPerintah['tujuan'] ?? '');
                if (content) {
                    quill.root.innerHTML = content;
                }

                // Sync with Livewire
                quill.on('text-change', () => {
                    @this.set('dataSuratPerintah.tujuan', quill.root.innerHTML);
                });
            };

            // Initialize on page load
            initQuill();

            // Reinitialize when Livewire navigates
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                if (el.id === 'surat-perintah-tujuan') {
                    initQuill();
                }
            });
        });
    </script>
@endpush
