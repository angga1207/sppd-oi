<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="">
                <h1 class="text-4xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">Selamat datang di Sistem Informasi SPPD</p>
            </div>

            <!-- Year Selection Dropdown for Admins -->
            @if (auth()->user()->role_id == 1)
                <div>
                    <label for="yearSelect" class="sr-only">Pilih Tahun</label>
                    <select id="yearSelect" wire:model.live="yearNow" class="form-select">
                        @foreach ($arrYears as $year)
                            <option value="{{ $year }}">Tahun {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Surat Perintah Tugas Card -->
            <div
                class="bg-gradient-to-br from-navy to-blue-light rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90 whitespace-nowrap">SPT Tahun Ini</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['total_surat_perintah'] }}</p>
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
                class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90 whitespace-nowrap">SPPD Tahun Ini</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['total_sppd'] }}</p>
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

            <!-- Current Month SPT Card -->
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90 whitespace-nowrap">SPT Bulan Ini</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['current_month_spt'] }}</p>
                        <p class="text-xs opacity-75 mt-2">{{ \Carbon\Carbon::now()->isoFormat('MMMM YYYY') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <x-heroicon-o-calendar class="w-8 h-8" />
                    </div>
                </div>
            </div>

            <!-- Current Month SPPD Card -->
            <div
                class="bg-gradient-to-br from-pink-500 to-pink-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90 whitespace-nowrap">SPPD Bulan Ini</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['current_month_sppd'] }}</p>
                        <p class="text-xs opacity-75 mt-2">{{ \Carbon\Carbon::now()->isoFormat('MMMM YYYY') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <x-heroicon-o-calendar-days class="w-8 h-8" />
                    </div>
                </div>
            </div>

            <!-- Active Trips Card -->
            <div
                class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90 whitespace-nowrap">Perjalanan Aktif</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['active_trips'] }}</p>
                        <p class="text-xs opacity-75 mt-2">Sedang berjalan</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <x-heroicon-o-map class="w-8 h-8" />
                    </div>
                </div>
            </div>
        </div>

        <hr class="mt-4 mb-6 border-gray-300">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Draft SPT Card -->
            <div
                class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">SPT Draft</p>
                        <p class="text-4xl font-bold mt-2">{{ $stats['draft_surat_perintah'] }}</p>
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
                        <p class="text-4xl font-bold mt-2">{{ $stats['sent_surat_perintah'] }}</p>
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
                        <p class="text-4xl font-bold mt-2">{{ $stats['approved_surat_perintah'] }}</p>
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
                        <p class="text-4xl font-bold mt-2">{{ $stats['rejected_surat_perintah'] }}</p>
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
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">Trend Surat Perintah Tugas</h3>
                <p class="mb-4 text-gray-600 text-xs">6 Bulan Terakhir</p>
                <div class="w-full" style="height: 300px;">
                    <livewire:livewire-area-chart :area-chart-model="$monthlySptChart" />
                </div>
            </div>

            <!-- Monthly SPPD Trend Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">Trend SPPD</h3>
                <p class="mb-4 text-gray-600 text-xs">6 Bulan Terakhir</p>
                <div class="w-full" style="height: 300px;">
                    <livewire:livewire-area-chart :area-chart-model="$monthlySppdChart" />
                </div>
            </div>
        </div>

        <!-- Distribution by Instance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Surat Perintah Tugas by Instance -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">Surat Perintah Tugas per Perangkat Daerah</h3>
                <p class="mb-4 text-gray-600 text-xs">Tahun {{ $yearNow }}</p>
                <div class="w-full" style="height: 300px;">
                    <livewire:livewire-column-chart :column-chart-model="$sptInstanceChart" />
                </div>
            </div>

            <!-- SPPD by Instance -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">SPPD per Perangkat Daerah</h3>
                <p class="mb-4 text-gray-600 text-xs">Tahun {{ $yearNow }}</p>
                <div class="w-full" style="height: 300px;">
                    <livewire:livewire-column-chart :column-chart-model="$sppdInstanceChart" />
                </div>
            </div>
        </div>

        <!-- New Charts: Trips Count & Cost Realization -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Trips Count Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <x-heroicon-o-chart-bar-square class="w-6 h-6 text-green-600 mr-2" />
                    Jumlah Perjalanan Dinas per Bulan
                </h3>
                <p class="text-xs text-gray-500 mb-4">12 Bulan Terakhir (Data Approved)</p>
                <div class="w-full" style="height: 300px;">
                    <livewire:livewire-column-chart :column-chart-model="$monthlyTripsChart" />
                </div>
            </div>

            <!-- Monthly Cost Realization Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <x-heroicon-o-banknotes class="w-6 h-6 text-orange-600 mr-2" />
                    Realisasi Biaya Perjalanan Dinas per Bulan
                </h3>
                <p class="text-xs text-gray-500 mb-4">12 Bulan Terakhir (Dummy Data)</p>
                <div class="w-full" style="height: 300px;">
                    <livewire:livewire-line-chart :line-chart-model="$monthlyCostChart" />
                </div>
            </div>
        </div>

        <!-- Top 5 OPD Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top 5 OPD by Cost -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <x-heroicon-o-currency-dollar class="w-6 h-6 text-green-600 mr-2" />
                    Top 5 OPD dengan Biaya Terbesar
                </h3>
                <div class="space-y-4">
                    @foreach ($top5OpdByCost as $index => $opd)
                        <div
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index == 0 ? 'bg-yellow-400 text-yellow-900' : ($index == 1 ? 'bg-gray-300 text-gray-900' : ($index == 2 ? 'bg-orange-400 text-orange-900' : 'bg-blue-100 text-blue-900')) }} font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $opd['instance_name'] }}</p>
                                    <p class="text-xs text-gray-500">Dummy Data</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">Rp
                                    {{ number_format($opd['total_cost'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top 5 OPD by Trip Count (Current Month) -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <x-heroicon-o-chart-bar class="w-6 h-6 text-indigo-600 mr-2" />
                    Top 5 OPD Jumlah Perjalanan Terbanyak
                </h3>
                <p class="text-xs text-gray-500 mb-4">Bulan {{ \Carbon\Carbon::now()->isoFormat('MMMM YYYY') }}</p>
                <div class="space-y-4">
                    @if ($top5OpdByCount->count() > 0)
                        @foreach ($top5OpdByCount as $index => $opd)
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index == 0 ? 'bg-yellow-400 text-yellow-900' : ($index == 1 ? 'bg-gray-300 text-gray-900' : ($index == 2 ? 'bg-orange-400 text-orange-900' : 'bg-blue-100 text-blue-900')) }} font-bold text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $opd['instance_name'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right w-[150px]">
                                    <p class="text-sm font-bold text-indigo-600">{{ $opd['total'] }} Perjalanan</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <x-heroicon-o-inbox class="mx-auto h-12 w-12 text-gray-400" />
                            <p class="mt-2">Belum ada data bulan ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Destination Charts: Province & Regency -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Province Destination Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <x-heroicon-o-map-pin class="w-6 h-6 text-blue-600 mr-2" />
                    Provinsi Tujuan Perjalanan Dinas
                </h3>
                <p class="text-xs text-gray-500 mb-4">Top 10 Provinsi (Data Approved)</p>
                <div class="w-full" style="height: 350px;">
                    <livewire:livewire-pie-chart :pie-chart-model="$provinceChart" />
                </div>
            </div>

            <!-- Regency Destination Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <x-heroicon-o-building-office-2 class="w-6 h-6 text-purple-600 mr-2" />
                    Kabupaten/Kota Tujuan Perjalanan Dinas
                </h3>
                <p class="text-xs text-gray-500 mb-4">Top 10 Kabupaten/Kota (Data Approved)</p>
                <div class="w-full" style="height: 350px;">
                    <livewire:livewire-pie-chart :pie-chart-model="$regencyChart" />
                </div>
            </div>
        </div>
    </div>
</div>
