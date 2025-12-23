<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">Selamat datang di Sistem Informasi SPPD</p>
            </div>
            {{-- select2 years --}}
            <div>
                <label for="yearSelect" class="sr-only">Pilih Tahun</label>
                <select id="yearSelect" wire:model.live="yearNow" class="form-select">
                    @foreach ($arrYears as $year)
                        <option value="{{ $year }}">Tahun {{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Surat Perintah Tugas Card -->
            <div
                class="md:col-span-2 bg-gradient-to-br from-navy to-blue-light rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total SPT</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total_surat_perintah'] }}</p>
                        <p class="text-xs opacity-75 mt-2">Tahun {{ $yearNow }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total SPPD Card -->
            <div
                class="md:col-span-2 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total SPPD</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total_sppd'] }}</p>
                        <p class="text-xs opacity-75 mt-2">Tahun {{ $yearNow }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Draft SPT Card -->
            <div
                class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">SPT Draft</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['draft_surat_perintah'] }}</p>
                        <p class="text-xs opacity-75 mt-2">Belum ditandatangani</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <x-heroicon-o-hand-raised class="w-8 h-8" />
                    </div>
                </div>
            </div>

            <!-- Sent SPT Card -->
            <div
                class="bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90 whitespace-nowrap">Menunggu Tanda Tangan</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['sent_surat_perintah'] }}</p>
                        <p class="text-xs opacity-75 mt-2">Menunggu tanda tangan</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <x-heroicon-o-clock class="w-8 h-8" />
                    </div>
                </div>
            </div>

            <!-- Approved SPT Card -->
            <div
                class="bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">SPT Ditandatangani</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['approved_surat_perintah'] }}</p>
                        <p class="text-xs opacity-75 mt-2">{{ $stats['surat_perintah_completion_rate'] }}%
                            ditandatangani</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <x-heroicon-o-finger-print class="w-8 h-8" />
                    </div>
                </div>
            </div>

            <!-- Rejected SPT Card -->
            <div
                class="bg-gradient-to-br from-red-400 to-rose-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">SPT Ditolak</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['rejected_surat_perintah'] }}</p>
                        <p class="text-xs opacity-75 mt-2">{{ $stats['surat_perintah_rejection_rate'] }}% ditolak</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <x-heroicon-o-x-mark class="h-8 w-8" />
                    </div>
                </div>
            </div>
        </div>

        <!-- SPPD Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Draft SPPD Card -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                        <x-heroicon-o-hand-raised class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">SPPD Draft</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['draft_sppd'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">&nbsp;</p>
                    </div>
                </div>
            </div>

            <!-- Sent SPPD Card -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                        <x-heroicon-o-clock class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 whitespace-nowrap">Menunggu Tanda Tangan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['sent_sppd'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">&nbsp;</p>
                    </div>
                </div>
            </div>


            <!-- Approved SPPD Card -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                        <x-heroicon-o-finger-print class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">SPPD Ditandatangani</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['approved_sppd'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['sppd_completion_rate'] }}% dari total</p>
                    </div>
                </div>
            </div>

            <!-- Rejected SPPD Card -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">SPPD Ditolak</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected_sppd'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->instance_id == null)
            <!-- Secondary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Employees -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border-l-4 border-navy transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pegawai</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_employees'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Instances -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-light transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-blue-light" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Perangkat Daerah</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_instances'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Charts and Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly SPT Trend Chart -->
            <div
                class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Surat Perintah Tugas 6 Bulan Terakhir</h3>
                @if ($monthlySuratPerintah->count() > 0)
                    <div class="space-y-3">
                        @php
                            $maxValue = $monthlySuratPerintah->max('total') ?: 1;
                        @endphp
                        @foreach ($monthlySuratPerintah as $data)
                            @php
                                $percentage = ($data->total / $maxValue) * 100;
                                $monthName = \Carbon\Carbon::parse($data->month)->format('M Y');
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $monthName }}</span>
                                    <span class="text-sm font-bold text-navy">
                                        {{ $data->total }} SPT
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-navy to-blue-light h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="mt-2">Belum ada data</p>
                    </div>
                @endif
            </div>

            <!-- Monthly SPPD Trend Chart -->
            <div
                class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend SPPD 6 Bulan Terakhir</h3>
                @if ($monthlySppd->count() > 0)
                    <div class="space-y-3">
                        @php
                            $maxSppdValue = $monthlySppd->max('total') ?: 1;
                        @endphp
                        @foreach ($monthlySppd as $data)
                            @php
                                $percentage = ($data->total / $maxSppdValue) * 100;
                                $monthName = \Carbon\Carbon::parse($data->month)->format('M Y');
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $monthName }}</span>
                                    <span class="text-sm font-bold text-accent">
                                        {{ $data->total }} SPPD
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-700 h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="mt-2">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Distribution by Instance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Surat Perintah Tugas by Instance -->
            <div
                class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Surat Perintah Tugas per Perangkat Daerah</h3>
                @if ($suratPerintahByInstance->count() > 0)
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @php
                            $maxInstanceValue = $suratPerintahByInstance->max('total') ?: 1;
                        @endphp
                        @foreach ($suratPerintahByInstance as $data)
                            @php
                                $percentage = ($data['total'] / $maxInstanceValue) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="text-sm font-medium text-gray-700 truncate pr-2">{{ $data['instance_name'] }}</span>
                                    <span class="text-sm font-bold text-navy">
                                        {{ $data['total'] }} SPT
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-navy to-blue-light h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="mt-2">Belum ada data</p>
                    </div>
                @endif
            </div>

            <!-- SPPD by Instance -->
            <div
                class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SPPD per Perangkat Daerah</h3>
                @if ($sppdByInstance->count() > 0)
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @php
                            $maxSppdInstanceValue = $sppdByInstance->max('total') ?: 1;
                        @endphp
                        @foreach ($sppdByInstance as $data)
                            @php
                                $percentage = ($data['total'] / $maxSppdInstanceValue) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="text-sm font-medium text-gray-700 truncate pr-2">{{ $data['instance_name'] }}</span>
                                    <span class="text-sm font-bold text-accent">
                                        {{ $data['total'] }} SPPD
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-700 h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="mt-2">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
