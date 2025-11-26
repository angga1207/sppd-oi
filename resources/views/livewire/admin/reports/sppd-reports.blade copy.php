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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total SPPD -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
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
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-muted">Total Anggaran</p>
                                <p class="text-3xl font-bold text-green-600">{{ 'Rp ' . number_format($totalBudgetUsed,
                                    0,
                                    ',', '.') }}</p>
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
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
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
                        <div class="flex items-center justify-between">
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
                        <div class="flex items-center justify-between">
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

            <!-- Data Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Detail Data SPPD
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Table Controls -->
                    <div class="table-controls">
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600">Tampilkan</label>
                            <select wire:model.live="perPage" class="entries-select">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <label class="text-sm text-gray-600">data per halaman</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600">Cari:</label>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari data SPPD..."
                                class="search-input">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th wire:click="sortBy('sppd_number')" class="cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            No. SPPD
                                            @if($sortColumn === 'sppd_number')
                                            @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            </svg>
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            @endif
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('employee_name')" class="cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Pegawai
                                            @if($sortColumn === 'employee_name')
                                            @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            </svg>
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            @endif
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('purpose')" class="cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Tujuan
                                            @if($sortColumn === 'purpose')
                                            @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            </svg>
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            @endif
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th class="mobile-hidden">Tanggal</th>
                                    <th class="mobile-hidden">Durasi</th>
                                    <th class="mobile-hidden">Transportasi</th>
                                    <th class="mobile-hidden">Sub Kegiatan</th>
                                    <th class="mobile-hidden">Kode Rekening</th>
                                    <th wire:click="sortBy('estimated_cost')" class="cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Anggaran
                                            @if($sortColumn === 'estimated_cost')
                                            @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            </svg>
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            @endif
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th wire:click="sortBy('status')" class="cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Status
                                            @if($sortColumn === 'status')
                                            @if($sortDirection === 'asc')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            </svg>
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            @endif
                                            @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                            @endif
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paginatedData as $item)
                                <tr>
                                    <td class="font-medium text-gray-900">
                                        {{ $item['sppd_number'] }}
                                    </td>
                                    <td>
                                        <div class="font-medium text-gray-900">{{ $item['employee_name'] }}</div>
                                        <div class="text-sm text-gray-500">NIP: {{ $item['employee_nip'] }}</div>
                                    </td>
                                    <td>
                                        <div class="text-gray-900 max-w-xs truncate">{{ $item['purpose'] }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $item['starting_place'] }} → {{ $item['destination_places'] }}
                                        </div>
                                    </td>
                                    <td class="mobile-hidden">
                                        <div>{{ $item['departure_date'] }}</div>
                                        <div class="text-gray-500">s/d {{ $item['return_date'] }}</div>
                                    </td>
                                    <td class="mobile-hidden">
                                        {{ $item['duration'] }} hari
                                    </td>
                                    <td class="mobile-hidden">
                                        {{ $item['transportation'] }}
                                    </td>
                                    <td class="mobile-hidden">
                                        <div class="max-w-xs truncate">{{ $item['sub_kegiatan'] }}</div>
                                    </td>
                                    <td class="mobile-hidden">
                                        {{ $item['kode_rekening'] }}
                                    </td>
                                    <td>
                                        Rp {{ number_format($item['estimated_cost'], 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                        $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-purple-100 text-purple-800'
                                        ];
                                        $statusLabels = [
                                        'draft' => 'Draft',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'completed' => 'Selesai'
                                        ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$item['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$item['status']] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-12">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                                            <p class="mt-1 text-sm text-gray-500">Tidak ada data SPPD yang sesuai dengan
                                                filter.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($paginatedData->count() > 0)
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Menampilkan {{ $paginatedData->firstItem() }} sampai {{ $paginatedData->lastItem() }} dari
                            {{ $paginatedData->total() }} data
                        </div>

                        <div class="pagination-buttons">
                            <button wire:click="previousPage" @if($paginatedData->onFirstPage()) disabled @endif
                                class="pagination-btn {{ $paginatedData->onFirstPage() ? 'disabled' : '' }}"
                                >
                                ❮❮
                            </button>
                            <button wire:click="previousPage" @if($paginatedData->onFirstPage()) disabled @endif
                                class="pagination-btn {{ $paginatedData->onFirstPage() ? 'disabled' : '' }}"
                                >
                                ❮
                            </button>

                            @foreach($paginatedData->getUrlRange(max(1, $paginatedData->currentPage() - 2),
                            min($paginatedData->lastPage(), $paginatedData->currentPage() + 2)) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})"
                                class="pagination-btn {{ $page == $paginatedData->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </button>
                            @endforeach

                            <button wire:click="nextPage" @if(!$paginatedData->hasMorePages()) disabled @endif
                                class="pagination-btn {{ !$paginatedData->hasMorePages() ? 'disabled' : '' }}"
                                >
                                ❯
                            </button>
                            <button wire:click="gotoPage({{ $paginatedData->lastPage() }})"
                                @if(!$paginatedData->hasMorePages()) disabled @endif
                                class="pagination-btn {{ !$paginatedData->hasMorePages() ? 'disabled' : '' }}"
                                >
                                ❯❯
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            get filteredData() {
            let data = @js($reportData);
            if (this.search) {
            data = data.filter(item =>
            item.sppd_number.toLowerCase().includes(this.search.toLowerCase()) ||
            item.employee_name.toLowerCase().includes(this.search.toLowerCase()) ||
            item.purpose.toLowerCase().includes(this.search.toLowerCase()) ||
            item.starting_place.toLowerCase().includes(this.search.toLowerCase()) ||
            item.destination_places.toLowerCase().includes(this.search.toLowerCase()) ||
            item.transportation.toLowerCase().includes(this.search.toLowerCase()) ||
            item.sub_kegiatan.toLowerCase().includes(this.search.toLowerCase()) ||
            item.kode_rekening.toLowerCase().includes(this.search.toLowerCase())
            );
            }

            if (this.sortColumn) {
            data.sort((a, b) => {
            let aVal = a[this.sortColumn];
            let bVal = b[this.sortColumn];

            if (typeof aVal === 'string') {
            aVal = aVal.toLowerCase();
            bVal = bVal.toLowerCase();
            }

            if (this.sortDirection === 'asc') {
            return aVal > bVal ? 1 : -1;
            } else {
            return aVal < bVal ? 1 : -1; } }); } return data; }, get paginatedData() { const start=(this.currentPage -
                1) * this.perPage; const end=start + this.perPage; return this.filteredData.slice(start, end); }, get
                totalPages() { return Math.ceil(this.filteredData.length / this.perPage); }, get startEntry() { return
                (this.currentPage - 1) * this.perPage + 1; }, get endEntry() { return Math.min(this.currentPage *
                this.perPage, this.filteredData.length); }, sort(column) { if (this.sortColumn===column) {
                this.sortDirection=this.sortDirection==='asc' ? 'desc' : 'asc' ; } else { this.sortColumn=column;
                this.sortDirection='asc' ; } this.currentPage=1; }, changePage(page) { if (page>= 1 && page <=
                    this.totalPages) { this.currentPage=page; } }, changePerPage() { this.currentPage=1; } }">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Detail Data SPPD
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Table Controls -->
                        <div class="table-controls">
                            <div class="flex items-center gap-2">
                                <label class="text-sm text-gray-600">Tampilkan</label>
                                <select x-model="perPage" @change="changePerPage()" class="entries-select">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <label class="text-sm text-gray-600">data per halaman</label>
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="text-sm text-gray-600">Cari:</label>
                                <input x-model="search" type="text" placeholder="Cari data SPPD..." class="search-input"
                                    @input="currentPage = 1">
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-container">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th @click="sort('sppd_number')" class="cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center gap-1">
                                                No. SPPD
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </div>
                                        </th>
                                        <th @click="sort('employee_name')" class="cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center gap-1">
                                                Pegawai
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </div>
                                        </th>
                                        <th @click="sort('purpose')" class="cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center gap-1">
                                                Tujuan
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </div>
                                        </th>
                                        <th class="mobile-hidden">Tanggal</th>
                                        <th class="mobile-hidden">Durasi</th>
                                        <th class="mobile-hidden">Transportasi</th>
                                        <th class="mobile-hidden">Sub Kegiatan</th>
                                        <th class="mobile-hidden">Kode Rekening</th>
                                        <th @click="sort('estimated_cost')" class="cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center gap-1">
                                                Anggaran
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </div>
                                        </th>
                                        <th @click="sort('status')" class="cursor-pointer hover:bg-gray-100">
                                            <div class="flex items-center gap-1">
                                                Status
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                </svg>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in paginatedData" :key="item.sppd_number">
                                        <tr>
                                            <td class="font-medium text-gray-900" x-text="item.sppd_number"></td>
                                            <td>
                                                <div class="font-medium text-gray-900" x-text="item.employee_name">
                                                </div>
                                                <div class="text-sm text-gray-500">NIP: <span
                                                        x-text="item.employee_nip"></span></div>
                                            </td>
                                            <td>
                                                <div class="text-gray-900 max-w-xs truncate" x-text="item.purpose">
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <span x-text="item.starting_place"></span> → <span
                                                        x-text="item.destination_places"></span>
                                                </div>
                                            </td>
                                            <td class="mobile-hidden">
                                                <div x-text="item.departure_date"></div>
                                                <div class="text-gray-500">s/d <span x-text="item.return_date"></span>
                                                </div>
                                            </td>
                                            <td class="mobile-hidden">
                                                <span x-text="item.duration"></span> hari
                                            </td>
                                            <td class="mobile-hidden" x-text="item.transportation"></td>
                                            <td class="mobile-hidden">
                                                <div class="max-w-xs truncate" x-text="item.sub_kegiatan"></div>
                                            </td>
                                            <td class="mobile-hidden" x-text="item.kode_rekening"></td>
                                            <td>
                                                Rp <span
                                                    x-text="new Intl.NumberFormat('id-ID').format(item.estimated_cost)"></span>
                                            </td>
                                            <td>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                    :class="{
                                                    'bg-gray-100 text-gray-800': item.status === 'draft',
                                                    'bg-green-100 text-green-800': item.status === 'approved',
                                                    'bg-red-100 text-red-800': item.status === 'rejected',
                                                    'bg-purple-100 text-purple-800': item.status === 'completed'
                                                }" x-text="{
                                                    'draft': 'Draft',
                                                    'approved': 'Disetujui',
                                                    'rejected': 'Ditolak',
                                                    'completed': 'Selesai'
                                                }[item.status] || 'Unknown'"></span>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State -->
                                    <tr x-show="filteredData.length === 0">
                                        <td colspan="10" class="text-center py-12">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                                                <p class="mt-1 text-sm text-gray-500">Tidak ada data SPPD yang sesuai
                                                    dengan
                                                    filter.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-container" x-show="filteredData.length > 0">
                            <div class="pagination-info">
                                Menampilkan <span x-text="startEntry"></span> sampai <span x-text="endEntry"></span>
                                dari
                                <span x-text="filteredData.length"></span> data
                            </div>

                            <div class="pagination-buttons">
                                <button @click="changePage(1)" :class="{ 'disabled': currentPage === 1 }"
                                    class="pagination-btn">
                                    ❮❮
                                </button>
                                <button @click="changePage(currentPage - 1)" :class="{ 'disabled': currentPage === 1 }"
                                    class="pagination-btn">
                                    ❮
                                </button>

                                <template x-for="page in Array.from({length: Math.min(5, totalPages)}, (_, i) => {
                                let start = Math.max(1, currentPage - 2);
                                let end = Math.min(totalPages, start + 4);
                                start = Math.max(1, end - 4);
                                return start + i;
                            }).filter(p => p <= totalPages)" :key="page">
                                    <button @click="changePage(page)" :class="{ 'active': currentPage === page }"
                                        class="pagination-btn" x-text="page"></button>
                                </template>

                                <button @click="changePage(currentPage + 1)"
                                    :class="{ 'disabled': currentPage === totalPages }" class="pagination-btn">
                                    ❯
                                </button>
                                <button @click="changePage(totalPages)"
                                    :class="{ 'disabled': currentPage === totalPages }" class="pagination-btn">
                                    ❯❯
                                </button>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Custom DataTable CSS -->
<style>
    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        height: 2.5rem;
        padding: 0.5rem 0.75rem;
        background-color: white;
        font-size: 0.875rem;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #0C2B4E;
        box-shadow: 0 0 0 3px rgba(12, 43, 78, 0.1);
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #374151;
        line-height: 1.5rem;
        padding: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 2.3rem;
        right: 0.5rem;
    }

    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0C2B4E;
        color: white;
    }

    /* Native Table Styling */
    .table-container {
        overflow-x: auto;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border-radius: 0.5rem;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
    }

    .custom-table thead th {
        background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .custom-table thead th:first-child {
        border-top-left-radius: 0.5rem;
    }

    .custom-table thead th:last-child {
        border-top-right-radius: 0.5rem;
    }

    .custom-table tbody tr {
        transition: all 0.2s ease-in-out;
        border-bottom: 1px solid #f3f4f6;
    }

    .custom-table tbody tr:hover {
        background-color: rgba(249, 250, 251, 0.8);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .custom-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 0.5rem;
    }

    .custom-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 0.5rem;
    }

    .custom-table tbody td {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: middle;
        border-right: 1px solid #f3f4f6;
    }

    .custom-table tbody td:last-child {
        border-right: none;
    }

    /* Table controls */
    .table-controls {
        display: flex;
        justify-content: between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 250px;
        max-width: 400px;
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: #0C2B4E;
        box-shadow: 0 0 0 3px rgba(12, 43, 78, 0.1);
    }

    .entries-select {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        background: white;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-top: 1rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .pagination-buttons {
        display: flex;
        gap: 0.25rem;
    }

    .pagination-btn {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background: white;
        color: #374151;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .pagination-btn:hover:not(.disabled) {
        background: #f9fafb;
        border-color: #0C2B4E;
        color: #0C2B4E;
    }

    .pagination-btn.active {
        background: #0C2B4E;
        border-color: #0C2B4E;
        color: white;
    }

    .pagination-btn.disabled {
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.5;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .table-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            min-width: 100%;
            max-width: 100%;
        }

        .pagination-container {
            flex-direction: column;
            text-align: center;
        }

        .custom-table {
            font-size: 0.75rem;
        }

        .custom-table thead th,
        .custom-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        /* Hide less important columns on mobile */
        .custom-table .mobile-hidden {
            display: none;
        }
    }

    @media (max-width: 640px) {

        .custom-table thead th,
        .custom-table tbody td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

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
