<div>

    <section class="bg-gradient-to-br from-navy via-blue-light to-cream py-20 sm:py-48 w-screen min-h-screen">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-6">
                    Sistem Informasi
                    <span class="block text-cream">Surat Perintah Perjalanan Dinas</span>
                </h1>
            </div>

            <div class="card max-w-7xl md:max-w-xl mx-auto border rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">
                    @if($typeData === 'surat_perintah')
                    Surat Perintah Tugas
                    @else
                    Surat Perintah Perjalanan Dinas
                    @endif
                </h2>
                <div class="space-y-4 mt-5">
                    <div class="p-4 border border-primary/40 rounded-lg bg-white/50 space-y-4">

                        @if($data->status == 'approved')
                        <div class="badge-success font-bold text-lg flex items-center gap-2 justify-center">
                            <x-heroicon-o-finger-print class="w-6 h-6" />
                            <span>
                                Surat ini telah ditandatangani
                            </span>
                        </div>
                        @elseif($data->status == 'draft')
                        <div class="badge-primary font-bold text-lg flex items-center gap-2 justify-center">
                            <x-heroicon-o-hand-raised class="w-6 h-6" />
                            <span>
                                Surat ini belum ditandatangani
                            </span>
                        </div>
                        @else
                        <div class="badge-danger font-bold text-lg flex items-center gap-2 justify-center">
                            <x-heroicon-o-x-circle class="w-6 h-6" />
                            <span>
                                Surat ini telah ditolak
                            </span>
                        </div>
                        @endif

                        <p class="text-xs">
                            Nomor: <br>
                            <span class="font-medium text-lg">{{ $data->nomor_surat }}</span>
                        </p>
                        <p class="text-xs">
                            Tanggal dibuat: <br>
                            <span class="font-medium text-lg">{{ $data->created_at->isoFormat('D MMMM YYYY - HH:mm
                                [WIB]') }}</span>
                        </p>
                        @if($data->status == 'approved' && $data->approved_at)
                        <p class="text-xs">
                            Ditandatangani pada: <br>
                            <span class="font-medium text-lg">{{ $data->approved_at->isoFormat('D MMMM YYYY - HH:mm
                                [WIB]') }}</span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
