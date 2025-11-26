<div>
    <div class="min-h-screen bg-light py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2
                        class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                        📊 Laporan SPPD
                    </h2>
                    <p class="mt-2 text-sm text-muted">Dashboard analitik dan laporan perjalanan dinas</p>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="resetFilters" class="btn-secondary">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                    <button wire:click="exportExcel" class="btn-primary">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-8">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                        </svg>
                        Filter Laporan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai
                            </label>
                            <input wire:model.live="dateFrom" type="date" class="form-input">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Akhir
                            </label>
                            <input wire:model.live="dateTo" type="date" class="form-input">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select wire:model.live="statusFilter" class="form-input">
                                <option value="">Semua Status</option>
                                <option value="draft">Draft</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                                <option value="completed">Selesai</option>
                            </select>
                        </div>

                        <!-- Instance Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Instansi
                            </label>
                            <div wire:ignore>
                                <select class="select2 form-input w-full" data-placeholder="-- Pilih Instansi --"
                                    id="instanceSelect">>
                                    <option value="">Semua Instansi</option>
                                    @foreach($instances as $instance)
                                    <option value="{{ $instance->id }}">{{ $instance->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
                <!-- Total SPPD -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-x-2 overflow-x-auto">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-muted">Total SPPD</p>
                                <p class="text-3xl font-bold text-primary">{{ number_format($totalSppd) }}</p>
                                <p class="text-xs text-green-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Periode ini
                                    </span>
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Budget -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-x-2 overflow-x-auto">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-muted">Total Anggaran</p>
                                <p class="text-3xl font-bold text-green-600 whitespace-nowrap">
                                    {{ 'Rp ' . number_format($totalBudgetUsed, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Estimasi biaya
                                    </span>
                                </p>
                            </div>
                            <div class="flex-none w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Duration -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-x-2 overflow-x-auto">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-muted">Rata-rata Durasi</p>
                                <p class="text-3xl font-bold text-orange-600">{{ number_format($avgTripDuration, 1) }}
                                </p>
                                <p class="text-xs text-orange-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Hari perjalanan
                                    </span>
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Month -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-x-2 overflow-x-auto">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-muted">Bulan Ini</p>
                                <p class="text-3xl font-bold text-purple-600">{{ number_format($currentMonthSppd) }}</p>
                                <p class="text-xs text-purple-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ date('M Y') }}
                                    </span>
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Monthly Trend Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Tren Bulanan SPPD
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="monthlyChart" class="w-full h-80"></canvas>
                    </div>
                </div>

                <!-- Status Distribution Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                            Distribusi Status
                        </h3>
                    </div>
                    <div class="p-6" wire:ignore>
                        <canvas id="statusChart" class="w-full h-80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Cost Level Chart -->
            <div class="card mb-8">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2" />
                        </svg>
                        Perbandingan per Instansi
                    </h3>
                </div>
                <div class="p-6" wire:ignore>
                    <canvas id="instanceChart" class="w-full h-80"></canvas>
                </div>
            </div>



        </div>

        <!-- SPPD Data Table -->
        <div class="card">
            <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Daftar SPPD
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Total: {{ $this->paginatedData->total() }} data</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 items-center">
                    <!-- Sort Options -->
                    {{-- <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Urutkan:</label>
                        <select wire:model.live="sortBy" class="form-input min-w-[120px] text-sm">
                            <option value="created_at-desc">Terbaru</option>
                            <option value="created_at-asc">Terlama</option>
                            <option value="nomor_sppd-asc">Nomor SPPD A-Z</option>
                            <option value="nomor_sppd-desc">Nomor SPPD Z-A</option>
                            <option value="tanggal_berangkat-desc">Tanggal Berangkat (Baru)</option>
                            <option value="tanggal_berangkat-asc">Tanggal Berangkat (Lama)</option>
                        </select>
                    </div> --}}

                    <!-- Per Page -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Per halaman:</label>
                        <select wire:model.live="perPage" class="form-input min-w-[80px] text-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Mobile Search (shown on mobile only) -->
                <div class="mb-4 sm:hidden">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Cari nomor SPPD, nama, tujuan..." class="form-input w-full pl-10">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-primary/5 to-primary/10 border-b border-primary/20">
                                <th class="text-left p-4 font-semibold text-primary border-r border-primary/10">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        Nomor SPPD
                                    </div>
                                </th>
                                <th class="text-left p-4 font-semibold text-primary border-r border-primary/10">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Nama Pegawai
                                    </div>
                                </th>
                                <th class="text-left p-4 font-semibold text-primary border-r border-primary/10">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Tujuan
                                    </div>
                                </th>
                                <th class="text-left p-4 font-semibold text-primary border-r border-primary/10">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-1 12a2 2 0 002 2h6a2 2 0 002-2L16 7m-6 0H4" />
                                        </svg>
                                        Tanggal
                                    </div>
                                </th>
                                <th class="text-left p-4 font-semibold text-primary border-r border-primary/10">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        Anggaran
                                    </div>
                                </th>
                                <th class="text-left p-4 font-semibold text-primary">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Status
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($this->paginatedData as $sppd)
                            <tr class="hover:bg-primary/5 transition-colors group">
                                <td class="p-4 border-r border-gray-100">
                                    <div class="font-medium text-primary group-hover:text-primary-dark">
                                        {{ $sppd['sppd_number'] }}
                                    </div>
                                </td>
                                <td class="p-4 border-r border-gray-100">
                                    <div class="font-medium text-gray-900">{{ $sppd['employee_name'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $sppd['employee_nip'] }}</div>
                                </td>
                                <td class="p-4 border-r border-gray-100">
                                    <div class="text-gray-900">{{ $sppd['destination_places'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $sppd['purpose'] }}</div>
                                </td>
                                <td class="p-4 border-r border-gray-100">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">
                                            Berangkat: {{ $sppd['departure_date'] }}
                                        </div>
                                        <div class="text-gray-500">
                                            Pulang: {{ $sppd['return_date'] }}
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-r border-gray-100">
                                    <div class="font-medium text-primary">
                                        Rp {{ number_format($sppd['estimated_cost'] ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($sppd['status'] === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Disetujui
                                    </span>
                                    @elseif($sppd['status'] === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Ditolak
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Draft
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-lg font-medium">Tidak ada data SPPD</p>
                                            <p class="text-sm">Belum ada data yang sesuai dengan filter yang dipilih.
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($this->paginatedData->hasPages())
                <div
                    class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-600 order-2 sm:order-1">
                        Menampilkan {{ $this->paginatedData->firstItem() ?? 0 }} sampai {{
                        $this->paginatedData->lastItem() ?? 0 }}
                        dari {{ $this->paginatedData->total() }} data
                    </div>

                    <div class="order-1 sm:order-2">
                        {{ $this->paginatedData->withQueryString()->links(data: ['scrollTo' => false]) }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Store chart instances to destroy them later
        let monthlyChart = null;
        let statusChart = null;
        let instanceChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();

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
        // Reinitialize when Livewire updates (Livewire 3)
        document.addEventListener('livewire:navigated', function() {
            setTimeout(() => {
                initializeCharts();
                initializeSelect2();
            }, 100);
        });

        // Listen for Livewire component updates
        Livewire.hook('morph.updated', ({ el, component }) => {
            if (component.name === 'admin.reports.sppd-reports') {
                setTimeout(() => {
                    initializeCharts();
                }, 100);
            }
        });

        // Alternative listener for component updates
        document.addEventListener('livewire:update', function() {
            setTimeout(() => {
                initializeCharts();
            }, 100);
        });

        function initializeSelect2() {
            const $ = jQuery; // Ensure we have jQuery
            if ($('#instanceSelect').hasClass('select2-hidden-accessible')) {
                $('#instanceSelect').select2('destroy');
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
                    @this.set('instanceFilter', value);
                });
                // console.log('Instance Select2 initialized');
            } catch (error) {
                console.error('Error initializing instanceSelect:', error);
            }
        }

        function initializeCharts() {
            try {
                // Destroy existing charts first
                if (monthlyChart) {
                    monthlyChart.destroy();
                    monthlyChart = null;
                }
                if (statusChart) {
                    statusChart.destroy();
                    statusChart = null;
                }
                if (instanceChart) {
                    instanceChart.destroy();
                    instanceChart = null;
                }

                // Monthly Trends Chart
                const monthlyCtx = document.getElementById('monthlyChart');
                if (monthlyCtx) {
                    // Clear canvas
                    monthlyCtx.getContext('2d').clearRect(0, 0, monthlyCtx.width, monthlyCtx.height);

                    const monthlyTrendsData = @json($monthlyTrendsData);

                    monthlyChart = new Chart(monthlyCtx, {
                        type: 'line',
                        data: {
                            labels: monthlyTrendsData.labels || [],
                            datasets: [{
                                label: 'Jumlah SPPD',
                                data: monthlyTrendsData.data || [],
                                borderColor: '#0C2B4E',
                                backgroundColor: 'rgba(12, 43, 78, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }

                // Status Distribution Chart
                const statusCtx = document.getElementById('statusChart');
                if (statusCtx) {
                    // Clear canvas
                    statusCtx.getContext('2d').clearRect(0, 0, statusCtx.width, statusCtx.height);

                    const statusDistributionData = @json($statusDistributionData);

                    statusChart = new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: statusDistributionData.labels || [],
                            datasets: [{
                                data: statusDistributionData.data || [],
                                backgroundColor: statusDistributionData.colors || [
                                    '#0C2B4E',
                                    '#1A3D64',
                                    '#FF6B6B',
                                    '#4ECDC4',
                                    '#45B7D1'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }

                // Instance Comparison Chart
                const instanceCtx = document.getElementById('instanceChart');
                if (instanceCtx) {
                    // Clear canvas
                    instanceCtx.getContext('2d').clearRect(0, 0, instanceCtx.width, instanceCtx.height);

                    const instanceComparisonData = @json($instanceComparisonData);

                    instanceChart = new Chart(instanceCtx, {
                        type: 'bar',
                        data: {
                            labels: instanceComparisonData.labels || [],
                            datasets: [
                                {
                                    label: 'Jumlah SPPD',
                                    data: instanceComparisonData.count_data || [],
                                    backgroundColor: 'rgba(12, 43, 78, 0.8)',
                                    borderColor: '#0C2B4E',
                                    borderWidth: 1,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Total Anggaran (Juta)',
                                    data: (instanceComparisonData.cost_data || []).map(cost => cost / 1000000),
                                    backgroundColor: 'rgba(26, 61, 100, 0.8)',
                                    borderColor: '#1A3D64',
                                    borderWidth: 1,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                x: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: 'Instansi'
                                    }
                                },
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    title: {
                                        display: true,
                                        text: 'Jumlah SPPD'
                                    },
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Anggaran (Juta Rupiah)'
                                    },
                                    beginAtZero: true,
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        }
    </script>
    @endpush
</div>
