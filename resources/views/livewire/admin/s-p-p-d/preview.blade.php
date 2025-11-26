<div class="min-h-screen bg-light py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Preview SPPD
                </h2>
                <p class="mt-2 text-sm text-muted">
                    Preview Surat Perintah Perjalanan Dinas (SPPD) {{ $previewData['nomor_sppd'] }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.sppd.edit', ['id' => $previewData['id']]) }}" class="btn-secondary">
                    <x-heroicon-o-pencil-square class="h-4 w-4 mr-2" />
                    Edit SPPD
                </a>
                <a href="{{ route('admin.surat-perintah.sppd', ['id' => $suratPerintahId]) }}" class="btn-secondary">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Daftar SPPD
                </a>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-xl font-bold text-navy flex items-center gap-2 p-4">
                Preview Data SPPD
            </h3>

            <div class="mt-4 border border-navy rounded-xl p-4 pb-10 bg-white shadow-lg relative select-none">
                <div
                    class="w-full h-full absolute top-0 left-0 bg-slate-400/20 flex items-center justify-center text-navy/50 text-4xl font-bold z-10 select-none pointer-events-none">
                    <span class="transform -rotate-45 text-center">PREVIEW SURAT PERINTAH PERJALANAN DINAS</span>
                </div>
                <h3 class="text-center m-0 underline text-2xl font-semibold">
                    SURAT PERINTAH PERJALANAN DINAS (SPPD)
                </h3>
                <p class="uppercase text-center m-0 font-semibold text-xl">
                    NOMOR : {{ $previewData['nomor_sppd'] }}
                </p>

                <div class="mt-4 overflow-auto">
                    <table class="w-full">
                        <tbody class="w-full">

                            {{-- <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    1.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    Pejabat yang memberi perintah
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>
                                        {{ $previewData['pejabat_jabatan'] }}
                                    </p>
                                </td>
                            </tr> --}}
                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    1.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    Dasar Pelaksanaan
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>
                                        Surat Perintah Tugas Nomor : {{ $previewData['nomor_surat_perintah'] }}
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    2.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    Nama / NIP Pegawai yang melaksanakan
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>
                                        {{ $previewData['pegawai_name'] }}
                                    </p>
                                    <p>
                                        {{ $previewData['pegawai_nip'] }}
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    3.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    <p>a. Pangkat dan Golongan</p>
                                    <p>b. Jabatan / Instansi</p>
                                    <p>c. Tingkat Biaya Perjalanan Dinas</p>
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>a. {{ $previewData['pegawai_pangkat'] }}</p>
                                    <p>b. {{ $previewData['pegawai_jabatan'] }}</p>
                                    <p>c. {{ $previewData['tingkat_biaya'] }}</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    4.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    Maksud Perjalanan Dinas
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p class="whitespace-preline">{!! $previewData['maksud_perjalanan']
                                        !!}</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    5.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    Alat Transportasi
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>{{ $previewData['alat_angkutan'] }}</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    6.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    <p>a. Tempat Berangkat</p>
                                    <p>b. Tempat Tujuan</p>
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>a. {{ $previewData['tempat_berangkat'] }}</p>
                                    <p>b. {{ $previewData['tempat_tujuan'] }}, {{ $previewData['regency_name'] }}, {{
                                        $previewData['province_name'] }} </p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    7.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    <p>a. Lamanya Perjalanan Dinas</p>
                                    <p>b. Tanggal Berangkat</p>
                                    <p>c. Tanggal Harus Kembali</p>
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>a. {{ $previewData['lama_perjalanan'] }} Hari</p>
                                    <p>b. {{ Carbon\Carbon::parse($previewData['tanggal_berangkat'])->isoFormat('dddd, D
                                        MMMM Y') }}</p>
                                    <p>c. {{ Carbon\Carbon::parse($previewData['tanggal_pulang'])->isoFormat('dddd, D
                                        MMMM Y') }}</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    8.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    <p>Pembebanan Anggaran :</p>
                                    <p>a. Instansi</p>
                                    <p>b. Mata Anggaran</p>
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>a. {{ $previewData['pembebanan_instansi'] }}</p>
                                    <p>b. {{ $previewData['kode_rekening'] }} - {{
                                        $previewData['uraian_rekening'] }}</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="min-w-[10px] border border-navy p-3 text-center">
                                    9.
                                </td>
                                <td class="min-w-[250px] border border-navy p-3 text-left">
                                    <p>Keterangan lain-lain</p>
                                </td>
                                <td class="min-w-[300px] border border-navy p-3 text-left">
                                    <p>{{ $previewData['keterangan_lain'] }}</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex items-center justify-center sm:justify-end">
                    <div class="flex flex-col items-center">
                        <table>
                            <tbody>
                                <tr class="whitespace-nowrap">
                                    <td>
                                        Dikeluarkan di
                                    </td>
                                    <td class="px-3">: </td>
                                    <td>
                                        {{ $previewData['publication_place'] ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="whitespace-nowrap">
                                    <td>
                                        Pada tanggal
                                    </td>
                                    <td class="px-3">: </td>
                                    <td>
                                        {{ $previewData['publication_date'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-center">
                            <div class="text-lg font-bold mt-4 whitespace-nowrap">
                                @if($previewData['issued_nip'] != '1000')
                                {{ $previewData['issued_jabatan_title'] }}
                                @elseif($previewData['issued_nip'] == '1000')
                                {{ $previewData['issued_jabatan'] }}
                                @endif
                            </div>
                            <div class="h-[75px]">

                            </div>
                            <div class="whitespace-nowrap font-bold">
                                {{ $previewData['issued_name'] }}
                            </div>
                            @if($previewData['issued_pangkat'] && $previewData['issued_golongan'])
                            <div class="whitespace-nowrap">
                                {{ $previewData['issued_pangkat'] }}
                                ({{ $previewData['issued_golongan'] }})
                            </div>
                            @endif
                            <div class="whitespace-nowrap">
                                @if($previewData['issued_nip'] != '1000')
                                NIP : {{ $previewData['issued_nip'] }}
                                {{-- @else
                                BUPATI KABUPATEN OGAN ILIR --}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
