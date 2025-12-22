<?php

use App\Models\SPPD;

?>

<div class="min-h-screen bg-light py-8">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Daftar SPPD
                </h2>
                <p class="mt-2 text-sm text-muted">
                    Kelola Surat Perintah Perjalanan Dinas (SPPD) untuk nomor surat
                    {{ $dataSuratPerintah->nomor_surat }}
                </p>
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

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 bg-gradient-to-r from-primary/5 to-secondary/10">
                <div class="overflow-x-auto scrollbar-hide">
                    <nav class="tab-nav" aria-label="Tabs">
                        <!-- Tab 1: Input -->
                        <a href="{{ route('admin.surat-perintah.edit', ['id' => $dataId]) }}"
                            class="tab-button min-w-fit flex-grow justify-center">
                            <x-heroicon-o-briefcase class="w-5 h-5 currentColor" />
                            <span class="hidden sm:inline">Form</span>
                            <span class="sm:hidden">Form</span>
                        </a>

                        <!-- Tab 2: SPPD -->
                        <a href="{{ route('admin.surat-perintah.sppd', ['id' => $dataId]) }}"
                            class="tab-button-active min-w-fit flex-grow justify-center">
                            <x-heroicon-o-users class="w-5 h-5 currentColor" />
                            <span class="hidden sm:inline">SPPD</span>
                            <span class="sm:hidden">SPPD</span>
                        </a>

                        <!-- Tab 3: Preview -->
                        <a href="{{ route('admin.surat-perintah.preview', ['id' => $dataId]) }}"
                            class="tab-button min-w-fit flex-grow justify-center">
                            <x-heroicon-o-photo class="w-5 h-5 currentColor" />
                            <span class="hidden sm:inline">Preview</span>
                            <span class="sm:hidden">Preview</span>
                        </a>

                        <!-- Tab 4: Logs -->
                        <a href="{{ route('admin.surat-perintah.logs', ['id' => $dataId]) }}"
                            class="tab-button min-w-fit flex-grow justify-center">
                            <x-heroicon-m-queue-list class="w-5 h-5 currentColor" />
                            <span class="hidden sm:inline">Log</span>
                            <span class="sm:hidden">Log</span>
                        </a>
                    </nav>
                </div>
            </div>

            <div class="p-4">
                @if ($dataSuratPerintah->status == 'draft')
                    <div class="card mb-8">
                        <div class="card-header">
                            <h3
                                class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent flex items-center">
                                <x-heroicon-o-plus class="w-5 h-5 text-primary" />
                                <span class="ml-2">
                                    Tambah SPPD
                                </span>
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Pilih Perangkat Daerah untuk API -->
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Perangkat Daerah
                                    </label>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <div class="flex-1" wire:ignore>
                                            <select wire:model="instanceSelected" class="select2 form-input"
                                                data-placeholder="-- Pilih Perangkat Daerah --" id="instanceSelected">
                                                <option value="">-- Pilih Perangkat Daerah --</option>
                                                @foreach ($instances as $inst)
                                                    <option value="{{ $inst['id'] }}">{{ $inst['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($instanceSelected != 0)
                                            <button type="button" wire:click="fetchSemestaUsers"
                                                wire:loading.attr="disabled"
                                                class="flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                                                <span wire:loading.remove wire:target="fetchSemestaUsers"
                                                    class="flex items-center">
                                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    <span>
                                                        Ambil Data
                                                    </span>
                                                </span>
                                                <span wire:loading wire:target="fetchSemestaUsers">
                                                    <svg class="animate-spin h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
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

                                <!-- Daftar Pegawai dari API -->
                                @if (count($rawSemestaUsers) > 0)
                                    <div
                                        class="col-span-1 md:col-span-2 mb-4 p-4 bg-blue-light bg-opacity-20 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Pegawai dari Semesta
                                        </label>

                                        <!-- Search Input for Employees -->
                                        <div class="mb-3 flex items-center justify-between">
                                            <div class="relative flex-1">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
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

                                        @if ($showEmployees)
                                            <div class="max-h-60 overflow-y-auto space-y-2">
                                                @forelse ($semestaUsers as $user)
                                                    <div wire:click="selectEmployee({{ json_encode($user) }})"
                                                        class="p-3 bg-white border border-gray-200 rounded-lg hover:bg-navy hover:text-white cursor-pointer transition duration-150">

                                                        <div class="flex items-center gap-2">
                                                            <p class="font-medium">
                                                                {{ $user['nama_lengkap'] ?? 'N/A' }}
                                                            </p>
                                                            @if ($user['kepala_skpd'] == 'Y')
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    Kepala OPD / Bagian
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm opacity-75">
                                                            NIP. {{ $user['nip'] ?? 'N/A' }} |
                                                            {{ $user['jabatan'] ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                @empty
                                                    <p class="text-sm text-gray-500">
                                                        Tidak ada pegawai yang ditemukan.
                                                    </p>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Informasi Pegawai yang Dipilih -->
                                @if ($selectedEmployee)
                                    <div
                                        class="col-span-1 md:col-span-2 mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <h4 class="text-sm font-semibold text-blue-800">
                                                    Pegawai yang Melaksanakan Perjalanan Dinas
                                                </h4>
                                            </div>
                                            <div class="space-y-1 text-sm">
                                                <p class="text-gray-900">
                                                    <span class="font-medium">Nama:</span>
                                                    <span
                                                        class="text-navy font-semibold">{{ $selectedEmployee['nama_lengkap'] ?? 'N/A' }}</span>
                                                    @if (isset($selectedEmployee['kepala_skpd']) && $selectedEmployee['kepala_skpd'] == 'Y')
                                                        <span
                                                            class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Kepala OPD
                                                        </span>
                                                    @endif
                                                </p>
                                                <p class="text-gray-700">
                                                    <span class="font-medium">NIP.</span>
                                                    {{ $selectedEmployee['nip'] ?? 'N/A' }}
                                                </p>
                                                <p class="text-gray-700">
                                                    <span class="font-medium">Jabatan:</span>
                                                    {{ $selectedEmployee['jabatan'] ?? 'N/A' }}
                                                </p>
                                                @if (isset($selectedEmployee['pangkat']) && $selectedEmployee['pangkat'])
                                                    <p class="text-gray-700">
                                                        <span class="font-medium">Pangkat:</span>
                                                        {{ $selectedEmployee['pangkat'] }}
                                                    </p>
                                                @endif
                                                @if (isset($selectedEmployee['eselon']) && $selectedEmployee['eselon'])
                                                    <p class="text-gray-700">
                                                        <span class="font-medium">Eselon:</span>
                                                        {{ $selectedEmployee['eselon'] }}
                                                    </p>
                                                @endif
                                                @if (isset($selectedEmployee['golongan']) && $selectedEmployee['golongan'])
                                                    <p class="text-gray-700">
                                                        <span class="font-medium">Golongan:</span>
                                                        {{ $selectedEmployee['golongan'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <div class="flex-1 mb-2">
                                                <label for="kodeRekening"
                                                    class="block text-sm font-medium text-gray-700">
                                                    Mata Anggaran <span class="text-red-500">*</span>
                                                </label>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ count($arrSubKegiatan) > 0 ? count($arrSubKegiatan) . ' Sub Kegiatan Ditemukan' : '' }}
                                                </div>
                                            </div>
                                            <div
                                                class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end sm:mb-4 gap-2">
                                                <div class="flex-none flex items-center gap-2">
                                                    <div class="" wire:ignore>
                                                        <select wire:model.live="selectedYear" id="selectedYear"
                                                            class="select2 form-input w-[150px] @error('selectedYear') border-red-500 @enderror"
                                                            data-placeholder="-- Pilih Tahun --">
                                                            <option value="">-- Pilih Tahun --</option>
                                                            @foreach ($availableYears as $year)
                                                                <option value="{{ $year }}">
                                                                    {{ $year }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="" wire:ignore>
                                                        <select wire:model.live="selectedMonth" id="selectedMonth"
                                                            class="select2 form-input w-[150px] @error('selectedMonth') border-red-500 @enderror"
                                                            data-placeholder="-- Pilih Bulan --">
                                                            <option value="">-- Pilih Bulan --</option>
                                                            @foreach ($availableMonths as $month => $monthName)
                                                                <option value="{{ $month }}">
                                                                    {{ $monthName }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    @if ($dataSuratPerintah->status == 'draft')
                                                        <button type="button" wire:click="fetchKodeRekening"
                                                            wire:loading.attr="disabled"
                                                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy disabled:opacity-50 transition duration-150">
                                                            <span wire:loading.remove wire:target="fetchKodeRekening"
                                                                class="flex items-center">
                                                                <svg class="h-5 w-5 mr-2" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                                <span>
                                                                    Ambil Pagu
                                                                </span>
                                                            </span>
                                                            <span wire:loading wire:target="fetchKodeRekening">
                                                                <svg class="animate-spin h-5 w-5 text-white"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
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
                                                                <svg class="h-5 w-5 mr-2" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                                <span>
                                                                    Ambil Pagu
                                                                </span>
                                                            </span>
                                                            <span wire:loading wire:target="fetchKodeRekening">
                                                                <svg class="animate-spin h-5 w-5 text-white"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="">
                                                    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2">
                                                        <div class="flex-1" wire:ignore x-data="{
                                                            arrSubKegiatan: @entangle('arrSubKegiatan'),
                                                            subKegiatan: @entangle('subKegiatan')
                                                        }"
                                                            x-init="let select = $('#subKegiatan');
                                                            select.select2({
                                                                theme: 'default',
                                                                width: '100%',
                                                                placeholder: '-- Pilih Sub Kegiatan --',
                                                                allowClear: true
                                                            }).on('change', function() {
                                                                subKegiatan = $(this).val();
                                                                $wire.set('subKegiatan', subKegiatan);
                                                                console.log(subKegiatan);
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
                                                            });">
                                                            <select id="subKegiatan"
                                                                class="form-input @error('subKegiatan') border-red-500 @enderror">
                                                                <option value="">-- Pilih Sub Kegiatan --
                                                                </option>
                                                                @foreach ($arrSubKegiatan as $subKegiatan)
                                                                    <option value="{{ $subKegiatan['fullcode'] }}">
                                                                        {{ $subKegiatan['fullcode'] . ' - ' . $subKegiatan['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @error('subKegiatan')
                                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="">
                                                    @if ($isLoadedKodeRekening)
                                                        @if ($subKegiatanData)
                                                            <div class="flex flex-col sm:flex-row gap-2">
                                                                <div class="flex-1" wire:ignore
                                                                    x-data="{
                                                                        arrKodeRekening: @entangle('arrKodeRekening'),
                                                                        kodeRekening: @entangle('kodeRekening')
                                                                    }" x-init="let select = $('#kodeRekening');
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
                                                                    });">
                                                                    <select id="kodeRekening"
                                                                        class="form-input @error('kodeRekening') border-red-500 @enderror">
                                                                        <option value="">-- Pilih Kode Rekening
                                                                            --
                                                                        </option>
                                                                        @foreach ($arrKodeRekening as $option)
                                                                            <option value="{{ $option['fullcode'] }}">
                                                                                {{ $option['fullcode'] . ' - ' . $option['name'] }}
                                                                                (Rp.
                                                                                {{ number_format($option['pagu_induk'], 0, ',', '.') }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @error('kodeRekening')
                                                                <p class="mt-2 text-sm text-red-600">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="hidden">
                                                @if ($subKegiatan && $subKegiatanData)
                                                    <div
                                                        class="mt-3 p-3 bg-indigo-50 border-l-4 border-indigo-400 rounded">
                                                        <div class="flex items-center">
                                                            <svg class="h-5 w-5 text-indigo-400 mr-2"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="text-sm text-indigo-700">
                                                                <strong>
                                                                    Sub Kegiatan
                                                                </strong>
                                                            </span>
                                                        </div>
                                                        <div class="mt-2 text-sm text-indigo-700">
                                                            <p>
                                                                <span class="font-medium">Kode:</span>
                                                                {{ $subKegiatanData['fullcode'] }}
                                                            </p>
                                                            <p>
                                                                <span class="font-medium">Uraian:</span>
                                                                {{ $subKegiatanData['name'] }}
                                                            </p>
                                                            {{-- <p>
                                                                <span class="font-medium">Pagu Induk:</span>
                                                                Rp.
                                                                {{ number_format($subKegiatanData['pagu_induk'] ?? 0, 0, ',', '.') }}
                                                            </p> --}}
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($kodeRekening && $kodeRekeningData)
                                                    <div
                                                        class="mt-3 p-3 bg-green-50 border-l-4 border-green-400 rounded">
                                                        <div class="flex items-center">
                                                            <svg class="h-5 w-5 text-green-400 mr-2"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="text-sm text-green-700">
                                                                <strong>
                                                                    Kode Rekening
                                                                </strong>
                                                            </span>
                                                        </div>
                                                        <div class="mt-2 text-sm text-green-700">
                                                            <p>
                                                                <span class="font-medium">Kode:</span>
                                                                {{ $kodeRekening }}
                                                            </p>
                                                            <p>
                                                                <span class="font-medium">Uraian:</span>
                                                                {{ $kodeRekeningData['name'] }}
                                                            </p>
                                                            <p>
                                                                <span class="font-medium">Pagu Induk:</span>
                                                                Rp.
                                                                {{ number_format($kodeRekeningData['pagu_induk'] ?? 0, 0, ',', '.') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>

                                        <div class="mt-4">
                                            <!-- Nomor SPPD -->
                                            <label for="sppd_number"
                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                Nomor SPPD <span class="text-red-500">*</span>
                                            </label>
                                            <div>
                                                <input wire:model="sppd_number" type="text" id="sppd_number"
                                                    class="form-input w-[400px] @error('sppd_number') border-red-500 @enderror"
                                                    autocomplete="off" placeholder="Masukkan nomor SPPD...">
                                                @error('sppd_number')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="mt-4 flex items-center justify-end">
                                            <button type="button" wire:click="confirmAddEmployee"
                                                class="btn-primary">
                                                <x-heroicon-o-plus class="w-5 h-5 mr-1" />
                                                Tambah Pegawai ke SPPD
                                            </button>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3
                            class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent flex items-center">
                            <x-heroicon-o-list-bullet class="w-5 h-5 text-primary" />
                            <span class="ml-2">
                                Daftar SPPD dalam Surat Perintah Tugas
                            </span>
                        </h3>
                    </div>

                    <div class="p-6 space-y-6">
                        @if (count($sppds) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-primary to-secondary">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                                SPPD
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                                Pegawai Pelaksana
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                                Pangkat / Golongan
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                                Tingkat Biaya
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                                Mata Anggaran
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($sppds as $index => $sppd)
                                            <tr class="hover:bg-primary/5 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center w-full">
                                                        <div
                                                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full {{ $sppd->status == 'draft' ? 'bg-primary' : 'bg-success' }} text-white font-semibold">
                                                            {{ $loop->iteration }}
                                                        </div>
                                                        <div class="ml-3 flex-1">
                                                            <div class="">
                                                                <div class="text-lg font-medium text-gray-900">
                                                                    {{ $sppd->nomor_sppd ?? 'Belum ada Nomor SPPD' }}
                                                                </div>
                                                            </div>

                                                            <div class="flex items-center justify-start gap-2">

                                                                <!-- Preview Button -->
                                                                {{-- <a href="{{ route('admin.sppd.preview', $sppd->id) }}"
                                                                    class="action-btn p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                                                                    data-tippy-content="Lihat SPPD">
                                                                    <x-heroicon-o-eye class="w-5 h-5 text-green-500" />
                                                                </a> --}}

                                                                <!-- Edit Button -->
                                                                <a href="{{ route('admin.sppd.edit', $sppd->id) }}"
                                                                    class="action-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                                                    data-tippy-content="Lihat SPPD">
                                                                    <x-heroicon-o-pencil-square
                                                                        class="w-5 h-5 text-blue-500" />
                                                                </a>

                                                                @if ($dataSuratPerintah->status == 'draft' && auth()->user()->id == $dataSuratPerintah->created_by)
                                                                    <!-- Delete Button -->
                                                                    <button
                                                                        wire:click="deleteSppd({{ $sppd->id }})"
                                                                        wire:confirm="Apakah Anda yakin ingin menghapus SPPD ini?"
                                                                        class="action-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                                                        data-tippy-content="Hapus SPPD">
                                                                        <x-heroicon-o-trash
                                                                            class="w-5 h-5 text-red-500" />
                                                                    </button>
                                                                @endif

                                                                <!-- Status Actions -->
                                                                {{-- @if (in_array(auth()->user()->role_id, [1, 2]) && $sppd->status === 'draft')
                                                        <button wire:click="updateStatus({{ $sppd->id }}, 'approved')"
                                                            class="action-btn p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                                                            data-tippy-content="Setujui SPPD">
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                        <button wire:click="updateStatus({{ $sppd->id }}, 'rejected')"
                                                            class="action-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                                            data-tippy-content="Tolak SPPD">
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                        @endif --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $sppd->employeeExecutor->nama_lengkap ?? '-' }}
                                                            </div>
                                                            <div class="text-xs text-muted whitespace-nowrap">
                                                                NIP. {{ $sppd->employeeExecutor->nip ?? '-' }}
                                                            </div>
                                                            <div class="text-xs text-gray-700 max-w-xs">
                                                                {{ $sppd->employeeExecutor->instance->name ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $sppd->employeeExecutor->pangkat ?? '-' }}
                                                        @if ($sppd->employeeExecutor && $sppd->employeeExecutor->golongan)
                                                            ({{ $sppd->employeeExecutor->golongan ?? '-' }})
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900 max-w-xs">
                                                        {{ SPPD::GetTingkatOptions($sppd->tingkat_biaya)['label'] ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900 max-w-xs">
                                                        @if ($sppd->kode_rekening && $sppd->uraian_rekening)
                                                            {{ $sppd->kode_rekening }} - {{ $sppd->uraian_rekening }}
                                                        @else
                                                            <a href="{{ route('admin.sppd.edit', ['id' => $sppd->id]) }}"
                                                                class="text-red-600 italic text-xs hover:underline">
                                                                Belum diterapkan
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-sm text-gray-500">
                                Belum ada SPPD yang ditambahkan ke Surat Perintah ini.
                            </p>
                        @endif
                    </div>

                </div>

                @if (count($sppds) > 0)
                    <div class="card mt-8 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if ($this->dataSuratPerintah->status == 'draft')
                                    <x-heroicon-o-information-circle class="w-6 h-6 text-yellow-500" />
                                    <p class="ml-2 text-sm text-yellow-700">
                                        Pastikan semua data SPPD sudah diisi dengan benar!
                                        <br>
                                        Menunggu persetujuan SPT dan SPPD untuk ditandatangani
                                        oleh Pejabat Berwenang.
                                    </p>
                                @elseif($this->dataSuratPerintah->status == 'sent')
                                    <x-heroicon-o-clock class="w-6 h-6 text-warning" />
                                    <p class="ml-2 text-sm text-warning">
                                        Surat Perintah dan SPPD telah dikirim untuk persetujuan.
                                        <br>
                                        Menunggu tindakan dari Pejabat Berwenang.
                                    </p>
                                @elseif($this->dataSuratPerintah->status == 'approved')
                                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-500" />
                                    <p class="ml-2 text-sm text-green-700">
                                        Surat Perintah dan SPPD telah ditandatangani.
                                        <br>
                                        Silakan cetak dokumen untuk ditandatangani
                                        oleh Pejabat Berwenang.
                                    </p>
                                @elseif($this->dataSuratPerintah->status == 'rejected')
                                    <x-heroicon-o-x-circle class="w-6 h-6 text-red-500" />
                                    <p class="ml-2 text-sm text-red-700">
                                        Surat Perintah dan SPPD ditolak.
                                        <br>
                                        Silakan periksa kembali data dan lakukan revisi jika diperlukan.
                                    </p>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.surat-perintah.preview', ['id' => $this->dataSuratPerintah->id]) }}"
                                    class="px-4 py-2 btn-primary whitespace-nowrap">
                                    Lanjut ke Preview
                                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

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

                // console.log('jQuery and Select2 loaded successfully!');
                initializeSelect2();
                initializeTippy();
            }, 100);
        });

        // Also initialize when Livewire is ready
        document.addEventListener('livewire:navigated', function() {
            // console.log('Livewire navigated, reinitializing Select2...');
            initializeSelect2();
        });

        function initializeSelect2() {
            const $ = jQuery; // Ensure we have jQuery

            if ($('#instanceSelected').hasClass('select2-hidden-accessible')) {
                $('#instanceSelected').select2('destroy');
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

            // Initialize Instance Select
            try {
                $('#instanceSelected').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: '-- Pilih Perangkat Daerah --',
                    allowClear: true
                }).on('change', function(e) {
                    const value = $(this).val();
                    // console.log('Instance changed to:', value);
                    @this.set('instanceSelected', value);
                });
                // console.log('Instance Select2 initialized');
            } catch (error) {
                console.error('Error initializing instanceSelected:', error);
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
        }


        // Initialize Tippy.js tooltips
        function initializeTippy() {
            if (typeof tippy !== 'undefined') {
                tippy('.action-btn', {
                    theme: 'custom',
                    animation: 'scale',
                    duration: [200, 150],
                    arrow: true,
                    placement: 'top',
                });
            }
        }

        // Listen for Livewire events
        window.addEventListener('select2:refresh', () => {
            setTimeout(() => {
                initializeSelect2();
                // console.log('Select2 refresh event received');
            }, 100);
        });
    </script>

    <style>
        /* Custom Tippy.js theme */
        .tippy-box[data-theme~='custom'] {
            background: linear-gradient(135deg, #0C2B4E 0%, #1A3D64 100%);
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }

        .tippy-box[data-theme~='custom'][data-placement^='top']>.tippy-arrow::before {
            border-top-color: #0C2B4E;
        }

        .tippy-box[data-theme~='custom'][data-placement^='bottom']>.tippy-arrow::before {
            border-bottom-color: #0C2B4E;
        }

        .tippy-box[data-theme~='custom'][data-placement^='left']>.tippy-arrow::before {
            border-left-color: #0C2B4E;
        }

        .tippy-box[data-theme~='custom'][data-placement^='right']>.tippy-arrow::before {
            border-right-color: #0C2B4E;
        }
    </style>
@endpush
