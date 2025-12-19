<div class="min-h-screen bg-light py-8">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Log Surat
                </h2>
                <p class="mt-2 text-sm text-muted">
                    Detail log perubahan status surat perintah tugas perjalanan dinas
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.surat-perintah.index') }}" class="btn-secondary">
                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-1" />
                    Daftar Surat Perintah Tugas
                </a>
            </div>
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
                            class="tab-button min-w-fit flex-grow justify-center">
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
                            class="tab-button-active min-w-fit flex-grow justify-center">
                            <x-heroicon-m-queue-list class="w-5 h-5 currentColor" />
                            <span class="hidden sm:inline">Log</span>
                            <span class="sm:hidden">Log</span>
                        </a>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <!-- Logs Content -->
                <h3 class="text-xl font-semibold mb-4">Log Status Surat</h3>
                <div class="space-y-4 max-h-[calc(100vh-450px)] overflow-y-auto">
                    @foreach ($logs as $log)
                        <div class="p-4 bg-slate-200 rounded-lg shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-2">
                                <div class="flex items-center gap-1">
                                    @if ($log->old_status)
                                        @if ($log->old_status == 'draft')
                                            <span class="badge-primary">
                                                <x-heroicon-o-hand-raised class="w-4 h-4 mr-1" />
                                                Draft
                                            </span>
                                        @elseif($log->old_status == 'sent')
                                            <span class="badge-warning">
                                                <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                                Menunggu Tanda Tangan
                                            </span>
                                        @elseif($log->old_status == 'approved')
                                            <span class="badge-success">
                                                <x-heroicon-o-finger-print class="w-4 h-4 mr-1" />
                                                Ditandatangani
                                            </span>
                                        @elseif($log->old_status == 'rejected')
                                            <span class="badge-danger">
                                                <x-heroicon-o-no-symbol class="w-4 h-4 mr-1" />
                                                Ditolak
                                            </span>
                                        @endif
                                        <x-heroicon-o-arrow-small-right class="w-4 h-4 text-gray-800" />
                                    @endif
                                    @if ($log->new_status == 'draft')
                                        <span class="badge-primary">
                                            <x-heroicon-o-hand-raised class="w-4 h-4 mr-1" />
                                            Draft
                                        </span>
                                    @elseif($log->new_status == 'sent')
                                        <span class="badge-warning">
                                            <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                            Menunggu Tanda Tangan
                                        </span>
                                    @elseif($log->new_status == 'approved')
                                        <span class="badge-success">
                                            <x-heroicon-o-finger-print class="w-4 h-4 mr-1" />
                                            Ditandatangani
                                        </span>
                                    @elseif($log->new_status == 'rejected')
                                        <span class="badge-danger">
                                            <x-heroicon-o-no-symbol class="w-4 h-4 mr-1" />
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center text-sm text-gray-500 whitespace-nowrap">
                                    <x-heroicon-m-calendar-days class="w-4 h-4 mr-1" />
                                    <span>
                                        {{ Carbon\Carbon::parse($log->created_at)->isoFormat('DD MMMM Y HH:mm [WIB]') }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <blockquote class="text-gray-900 italic font-semibold tracking-tight text-heading">
                                    {{ $log->keterangan }}
                                </blockquote>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
