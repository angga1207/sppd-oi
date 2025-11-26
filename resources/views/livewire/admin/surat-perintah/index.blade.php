<div class="min-h-screen bg-light py-8">
    <div class=mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Daftar Surat Perintah
                </h2>
                <p class="mt-2 text-sm text-muted">Kelola Surat Perintah</p>
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
                <a href="{{ route('admin.surat-perintah.create') }}"
                    class="btn-primary inline-flex items-center justify-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Surat Perintah Baru
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
                        @if($search || $statusFilter || $instanceFilter || $dateFilter)
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
                    <div class="">
                        <label class="form-label">
                            <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Pencarian
                        </label>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-input"
                            placeholder="Cari nomor Surat Perintah, nama pegawai, NIP, atau tujuan...">
                    </div>

                    <!-- Instance Filter -->
                    @if(auth()->user()->instance_id == null)
                    <div wire:ignore>
                        <label class="form-label">Perangkat Daerah</label>
                        <select id="instanceFilter" class="form-select select2-filter" style="width: 100%">
                            <option value="">Semua Perangkat Daerah</option>
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

                    <!-- Start Date Filter -->
                    <div>
                        <label class="form-label">Tanggal Ditetapkan</label>
                        <input wire:model.live="dateFilter" type="date" class="form-input">
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
            </div>
        </div>


        <!-- Table / Card View -->
        @if($datas->count() > 0)
        <div class="card overflow-hidden">
            <!-- Desktop Table View -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-primary to-secondary">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                No. Surat Perintah
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Pejabat Pemberi Perintah
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                SPPD
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
                        @foreach($datas as $index => $data)
                        <tr class="hover:bg-primary/5 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full {{ $data->status == 'draft' ? 'bg-gradient-to-r from-primary to-secondary' : 'bg-gradient-to-r from-success to-success/75' }} text-white font-semibold">
                                        {{ $datas->firstItem() + $index }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $data->nomor_surat }}
                                            </div>
                                            @if($data->instance)
                                            <div class="text-xs text-muted truncate max-w-[300px] action-btn cursor-pointer"
                                                data-tippy-content="{{ $data->instance->name ?? 'N/A' }}"
                                                data-tippy-placement="right">
                                                {{ $data->instance->name ?? 'N/A' }}
                                            </div>
                                            @else
                                            <div class="text-xs text-muted">
                                                Bupati Ogan Ilir
                                            </div>
                                            @endif
                                        </div>

                                        <div class="flex items-center justify-start gap-2 mt-2">
                                            <!-- View Button -->
                                            <a href="{{ route('admin.surat-perintah.preview', $data->id) }}"
                                                class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition duration-150"
                                                title="Preview Surat Perintah">
                                                <x-heroicon-o-eye class="w-5 h-5 text-purple-500" />
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.surat-perintah.edit', $data->id) }}"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                                                title="Edit Surat Perintah">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <!-- SPPD Button -->
                                            <a href="{{ route('admin.surat-perintah.sppd', $data->id) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-150"
                                                title="Lihat SPPD">
                                                <x-heroicon-o-users class="w-5 h-5 text-blue-500" />
                                            </a>

                                            @if($data->status == 'draft' && auth()->user()->id == $data->created_by)
                                            <!-- Delete Button -->
                                            <button wire:click="deleteSppd({{ $data->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus Surat Perintah ini?"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-150"
                                                title="Hapus Surat Perintah">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            @endif

                                            <!-- Status Actions -->
                                            @if(in_array(auth()->user()->role_id, [1,2]) && $data->status === 'draft')
                                            <!-- Tanda Tangan Elektronik Button -->
                                            {{-- <button wire:click="openModalSign({{ $data->id }})"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-150"
                                                title="Tanda Tangani Surat Perintah">
                                                <x-heroicon-o-shield-check class="w-5 h-5 text-blue-500" />
                                            </button> --}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $data->employeeGiver->nama_lengkap ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-muted whitespace-nowrap">
                                            NIP: {{ $data->employeeGiver->nip ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ $data->sppds->count() ?? 'Tidak Ada' }} SPPD
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
                                        {{ \Carbon\Carbon::parse($data->tanggal_berangkat)->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    s/d {{ \Carbon\Carbon::parse($data->tanggal_pulang)->format('d M Y') }}
                                </div>
                                <div class="">
                                    {{-- duration --}}
                                    <span class="text-xs text-muted">
                                        ({{ $data->lama_perjalanan }} hari)
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    <div class="font-medium line-clamp-2">
                                        {{ Str::limit($data->tempat_tujuan) }}
                                    </div>
                                    <div class="text-xs text-muted flex items-center gap-1 mt-1">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="whitespace-nowrap">
                                            {{ $data->alat_angkutan }}
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
                                $statusClass = $statusClasses[$data->status] ?? 'badge-primary';
                                @endphp
                                <span class="{{ $statusClass }}">
                                    {{ ucfirst($data->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer">
                {{ $datas->links('vendor.livewire.custom-pagination') }}
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
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada Surat Perintah</h3>
                    <p class="text-sm text-muted mb-6">
                        @if($search || $statusFilter || $instanceFilter || $dateFilter)
                        Tidak ditemukan Surat Perintah dengan filter yang dipilih. Coba ubah atau reset filter.
                        @else
                        Belum ada Surat Perintah yang dibuat. Mulai dengan membuat Surat Perintah baru.
                        @endif
                    </p>
                    @if($search || $statusFilter || $instanceFilter || $dateFilter)
                    <button wire:click="resetFilters" class="btn-secondary">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                    @else
                    <a href="{{ route('admin.surat-perintah.create') }}" class="btn-primary">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Surat Perintah Pertama
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Modal Tanda Tangan Elektronik -->
    <div x-data="{ showSignModal: @entangle('showSignModal') }" x-show="showSignModal" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Background backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showSignModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true" @click="showSignModal = false"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showSignModal"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <form wire:submit.prevent="processDigitalSignature">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white" id="modal-title">
                                        Tanda Tangan Elektronik
                                    </h3>
                                    <p class="text-sm text-blue-100">
                                        Masukkan passphrase untuk menandatangani surat perintah
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeModalSign"
                                class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6">
                        <!-- Informasi Surat Perintah -->
                        @if($selectedSppdForSign)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Informasi Surat Perintah
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nomor Surat:</span>
                                    <span class="font-medium text-gray-900">{{ $selectedSppdForSign['nomor_surat'] ??
                                        'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Pemberi Perintah:</span>
                                    <span class="font-medium text-gray-900">{{
                                        $selectedSppdForSign['employeeGiver']['nama_lengkap'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal:</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $selectedSppdForSign ?
                                        \Carbon\Carbon::parse($selectedSppdForSign['tanggal_berangkat'])->format('d M
                                        Y')
                                        : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Input Passphrase -->
                        <div class="space-y-4">
                            <div>
                                <label for="signPassphrase"
                                    class="block text-sm font-medium text-gray-700 mb-2 select-none">
                                    Passphrase Tanda Tangan Elektronik
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative" x-data="{ showPassphrase: false }">
                                    <input wire:model="signPassphrase" id="signPassphrase"
                                        class="form-input w-full pr-10 @error('signPassphrase') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                        placeholder="Masukkan passphrase Anda..." autocomplete="new-passphrase"
                                        :type="showPassphrase ? 'text' : 'password'">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                        :class="showPassphrase ? 'text-blue-600' : 'text-gray-400'"
                                        @click="showPassphrase = !showPassphrase">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('signPassphrase')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 select-none">
                                    <svg class="w-3 h-3 inline mr-1 text-yellow-500" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Pastikan passphrase yang dimasukkan benar untuk melakukan tanda tangan elektronik
                                </p>
                            </div>
                        </div>

                        <!-- Security Notice -->
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg select-none">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="text-sm">
                                    <p class="text-blue-800 font-medium">Catatan Keamanan:</p>
                                    <ul class="text-blue-700 mt-1 space-y-1 text-xs">
                                        <li>• Tanda tangan elektronik bersifat legal dan mengikat</li>
                                        <li>• Pastikan Anda memiliki kewenangan untuk menandatangani dokumen ini</li>
                                        <li>• Simpan passphrase Anda dengan aman</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit" wire:loading.attr="disabled" wire:target="processDigitalSignature"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                            <span wire:loading.remove wire:target="processDigitalSignature" class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Tanda Tangani Sekarang
                            </span>
                            <span wire:loading wire:target="processDigitalSignature" class="flex items-center">
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                        <button type="button" wire:click="closeModalSign"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for all filter selects
        function initializeSelect2() {
            // Instance Filter
            $('#instanceFilter').select2({
                placeholder: 'Semua Perangkat Daerah',
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

{{-- <style>
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
</style> --}}
@endpush
