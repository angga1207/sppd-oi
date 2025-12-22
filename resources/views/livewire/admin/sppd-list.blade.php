<div class="min-h-screen bg-light py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Daftar SPPD
                </h2>
                <p class="mt-2 text-sm text-muted">Kelola Surat Perintah Perjalanan Dinas</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <button wire:click="exportExcel" class="btn-secondary inline-flex items-center justify-center"
                    wire:loading.attr="disabled" wire:target="exportExcel">
                    <svg wire:loading.remove wire:target="exportExcel" class="h-5 w-5 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <svg wire:loading wire:target="exportExcel" class="animate-spin h-5 w-5 mr-2" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
                    <span wire:loading wire:target="exportExcel">Memproses...</span>
                </button>
                <a href="{{ route('admin.sppd.create') }}" class="btn-primary inline-flex items-center justify-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat SPPD Baru
                </a>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card mb-6" x-data={showFilters:false}>
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter & Pencarian
                    </h3>
                    <div class="flex items-center justify-end gap-2">
                        @if($search || $statusFilter || $instanceFilter || $startDateFilter || $endDateFilter)
                        <button wire:click="resetFilters"
                            class="btn-accent inline-flex items-center justify-center text-sm px-3 py-1">
                            Reset Filter
                        </button>
                        @endif
                        <button @click="showFilters = !showFilters"
                            class="btn-primary inline-flex items-center justify-center text-sm px-3 py-1">
                            <span x-show="!showFilters">Tampilkan Filter</span>
                            <span x-show="showFilters">Sembunyikan Filter</span>
                        </button>
                    </div>
                </div>
            </div>
            <div x-show="showFilters" x-cloak class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-4">
                    <!-- Search -->
                    <div class="lg:col-span-3">
                        <label class="form-label">
                            <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Pencarian
                        </label>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-input"
                            placeholder="Cari nomor SPPD, nama pegawai, NIP, atau tujuan...">
                    </div>

                    <!-- Instance Filter -->
                    @if(auth()->user()->instance_id == null)
                    <div wire:ignore>
                        <label class="form-label">Instansi</label>
                        <select id="instanceFilter" class="form-select select2-filter" style="width: 100%">
                            <option value="">Semua Instansi</option>
                            @foreach($instances as $instance)
                            <option value="{{ $instance->id }}" {{ $instanceFilter==$instance->id ? 'selected' : ''
                                }}>{{ $instance->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Status Filter -->
                    <div wire:ignore>
                        <label class="form-label">Status</label>
                        <select id="statusFilter" class="form-select select2-filter" style="width: 100%">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ $statusFilter=='draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ $statusFilter=='approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $statusFilter=='rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ $statusFilter=='completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    {{-- <div wire:ignore>
                        <label class="form-label">Tampilkan Per Halaman</label>
                        <select id="perPage" class="form-select select2-filter" style="width: 100%">
                            <option value="10" {{ $perPage==10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage==25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage==50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage==100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div> --}}
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <!-- Start Date Filter -->
                    <div>
                        <label class="form-label">Tanggal Berangkat (Dari)</label>
                        <input wire:model.live="startDateFilter" type="date" class="form-input">
                    </div>

                    <!-- End Date Filter -->
                    <div>
                        <label class="form-label">Tanggal Pulang (Sampai)</label>
                        <input wire:model.live="endDateFilter" type="date" class="form-input">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table / Card View -->
        @if($sppds->count() > 0)
        <div class="card overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-primary to-secondary">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                No. SPPD
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Pejabat Pemberi Perintah
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Pegawai Pelaksana
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Tujuan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sppds as $index => $sppd)
                        <tr class="hover:bg-primary/5 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gradient-to-r from-primary to-secondary text-white font-semibold">
                                        {{ $sppds->firstItem() + $index }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $sppd->nomor_sppd }}
                                        </div>
                                        <div class="text-xs text-muted truncate max-w-[300px] action-btn cursor-pointer"
                                            data-tippy-content="{{ $sppd->instance->name ?? '-' }}"
                                            data-tippy-placement="right">
                                            {{ $sppd->instance->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-2">

                                    <!-- Preview Button -->
                                    <a href="{{ route('admin.sppd.preview', $sppd->id) }}"
                                        class="action-btn p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                                        data-tippy-content="Lihat SPPD">
                                        <x-heroicon-o-eye class="w-5 h-5 text-green-500" />
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.sppd.edit', $sppd->id) }}"
                                        class="action-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                        data-tippy-content="Edit SPPD">
                                        <x-heroicon-o-pencil-square class="w-5 h-5 text-blue-500" />
                                    </a>

                                    <!-- Delete Button -->
                                    <button wire:click="deleteSppd({{ $sppd->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus SPPD ini?"
                                        class="action-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                        data-tippy-content="Hapus SPPD">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                    <!-- Status Actions -->
                                    @if(in_array(auth()->user()->role_id, [1,2]) && $sppd->status === 'draft')
                                    <button wire:click="updateStatus({{ $sppd->id }}, 'approved')"
                                        class="action-btn p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                                        data-tippy-content="Setujui SPPD">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    <button wire:click="updateStatus({{ $sppd->id }}, 'rejected')"
                                        class="action-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                        data-tippy-content="Tolak SPPD">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $sppd->employeeGiver->nama_lengkap ?? '-' }}
                                        </div>
                                        <div class="text-xs text-muted whitespace-nowrap">
                                            NIP. {{ $sppd->employeeGiver->nip ?? '-' }}
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
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="flex items-center gap-1">
                                        <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($sppd->tanggal_berangkat)->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    s/d {{ \Carbon\Carbon::parse($sppd->tanggal_pulang)->format('d M Y') }}
                                </div>
                                <div class="">
                                    {{-- duration --}}
                                    <span class="text-xs text-muted">
                                        ({{ $sppd->lama_perjalanan }} hari)
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    <div class="font-medium truncate">{{ Str::limit($sppd->tempat_tujuan, 40) }}</div>
                                    <div class="text-xs text-muted flex items-center gap-1 mt-1">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="whitespace-nowrap">
                                            {{ $sppd->alat_angkutan }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $statusClasses = [
                                'draft' => 'badge-primary',
                                'approved' => 'badge-success',
                                'rejected' => 'badge-danger',
                                'completed' => 'badge-success',
                                ];
                                $statusClass = $statusClasses[$sppd->status] ?? 'badge-primary';
                                @endphp
                                <span class="{{ $statusClass }}">
                                    {{ ucfirst($sppd->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden divide-y divide-gray-200">
                @foreach($sppds as $index => $sppd)
                <div class="p-4 hover:bg-primary/5 transition duration-150">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gradient-to-r from-primary to-secondary text-white font-semibold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $sppd->nomor_sppd }}</div>
                                <div class="text-xs text-muted">{{ $sppd->instance->name ?? '-' }}</div>
                            </div>
                        </div>
                        @php
                        $statusClasses = [
                        'pending' => 'badge-warning',
                        'approved' => 'badge-primary',
                        'rejected' => 'badge-danger',
                        'completed' => 'badge-success',
                        ];
                        $statusClass = $statusClasses[$sppd->status] ?? 'badge-primary';
                        @endphp
                        <span class="{{ $statusClass }}">
                            {{ ucfirst($sppd->status) }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="space-y-2 mb-3">
                        <!-- Pejabat -->
                        <div class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div class="flex-1">
                                <div class="text-xs text-muted">Pejabat Pemberi Perintah</div>
                                <div class="text-sm font-medium text-gray-900">{{ $sppd->employeeGiver->nama_lengkap ??
                                    '-' }}</div>
                            </div>
                        </div>

                        <!-- Pegawai -->
                        <div class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div class="flex-1">
                                <div class="text-xs text-muted">Pegawai Pelaksana</div>
                                <div class="text-sm font-medium text-gray-900">{{ $sppd->employeeExecutor->nama_lengkap
                                    ?? '-' }}</div>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div class="flex-1">
                                <div class="text-xs text-muted">Periode Perjalanan</div>
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($sppd->tanggal_berangkat)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($sppd->tanggal_pulang)->format('d M Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Tujuan -->
                        <div class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div class="flex-1">
                                <div class="text-xs text-muted">Tujuan</div>
                                <div class="text-sm text-gray-900">{{ $sppd->tempat_tujuan }}</div>
                                <div class="text-xs text-muted mt-1">{{ $sppd->alat_angkutan }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                        <button wire:click="viewDetail({{ $sppd->id }})"
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                            title="Lihat Detail">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <button wire:click="duplicateSppd({{ $sppd->id }})"
                            class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition duration-150"
                            title="Duplikat">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <a href="{{ route('admin.sppd.edit', $sppd->id) }}"
                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                            title="Edit">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <button wire:click="deleteSppd({{ $sppd->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus SPPD ini?"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-150" title="Hapus">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        @if($sppd->status === 'pending')
                        <button wire:click="updateStatus({{ $sppd->id }}, 'approved')"
                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                            title="Setujui">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        <button wire:click="updateStatus({{ $sppd->id }}, 'rejected')"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-150" title="Tolak">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="card-footer">
                {{ $sppds->links('vendor.livewire.custom-pagination') }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body">
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-muted mb-4">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada SPPD</h3>
                    <p class="text-sm text-muted mb-6">
                        @if($search || $statusFilter || $instanceFilter || $startDateFilter || $endDateFilter)
                        Tidak ditemukan SPPD dengan filter yang dipilih. Coba ubah atau reset filter.
                        @else
                        Belum ada SPPD yang dibuat. Mulai dengan membuat SPPD baru.
                        @endif
                    </p>
                    @if($search || $statusFilter || $instanceFilter || $startDateFilter || $endDateFilter)
                    <button wire:click="resetFilters" class="btn-secondary">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                    @else
                    <a href="{{ route('admin.sppd.create') }}" class="btn-primary">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat SPPD Pertama
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for all filter selects
        function initializeSelect2() {
            // Instance Filter
            $('#instanceFilter').select2({
                placeholder: 'Semua Instansi',
                allowClear: true,
                width: '100%',
                theme: 'default'
            }).on('change', function(e) {
                @this.set('instanceFilter', $(this).val());
            });

            // Status Filter
            $('#statusFilter').select2({
                placeholder: 'Semua Status',
                allowClear: true,
                width: '100%',
                theme: 'default',
                minimumResultsForSearch: -1 // Disable search for status
            }).on('change', function(e) {
                @this.set('statusFilter', $(this).val());
            });

            // Per Page
            $('#perPage').select2({
                placeholder: 'Pilih jumlah',
                width: '100%',
                theme: 'default',
                minimumResultsForSearch: -1 // Disable search for per page
            }).on('change', function(e) {
                @this.set('perPage', $(this).val());
            });
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

        // Initialize on page load
        initializeSelect2();
        initializeTippy();

        // Reinitialize after Livewire updates
        Livewire.hook('morph.updated', ({ el, component }) => {
            initializeSelect2();
            initializeTippy();
        });

        // Listen for reset filter event to clear Select2
        window.addEventListener('livewire:init', () => {
            Livewire.on('filtersReset', () => {
                $('#instanceFilter').val('').trigger('change');
                $('#statusFilter').val('').trigger('change');
                $('#perPage').val('10').trigger('change');
            });
        });
    });
</script>
@endpush
