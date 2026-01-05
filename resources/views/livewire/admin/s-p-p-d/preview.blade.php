<div class="">
    <div class="flex sm:items-center sm:justify-between flex-col sm:flex-row gap-2 p-4">
        <h3 class="text-xl font-bold text-navy flex items-center gap-2">
            Preview Data SPPD
        </h3>

        @if ($previewData['status'] === 'approved')
            <div class="flex gap-2 flex-wrap">
                <button type="button" class="btn-sm btn-success" wire:click="downloadSuratPerintah">
                    <x-heroicon-o-cloud-arrow-down class="w-4 h-4 mr-1" />
                    Unduh SPPD ({{ $previewData['nomor_sppd'] }})
                </button>
            </div>
        @endif
    </div>

    @if ($previewData['status'] === 'approved')
        <div class="mt-4 border border-navy rounded-xl p-4 pb-10 bg-white shadow-lg relative">
            <iframe src="{{ asset('storage/sppd_sign/' . $previewData['file_pdf_signed']) }}"
                class="w-full h-[800px] rounded-xl border border-navy"></iframe>
        </div>
    @else
        <div class="mt-4 border border-navy rounded-xl p-4 pb-10 bg-white shadow-lg relative select-none">
            <div
                class="w-full h-full absolute top-0 left-0 bg-slate-400/20 rounded-xl flex items-center justify-center text-navy/50 text-4xl font-bold z-10 select-none pointer-events-none">
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
                    <tbody class="w-full align-top">
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

                        <!-- BAGIAN 3 START -->
                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 pt-3 text-center">
                                3.
                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 pt-3 text-left">
                                <p>a. Pangkat dan Golongan</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 pt-3 text-left">
                                <p>
                                    a. {{ $previewData['pegawai_pangkat'] }}
                                    {{ $previewData['pegawai_golongan'] ? '(' . $previewData['pegawai_golongan'] . ')' : '' }}
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 text-center">

                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 text-left">
                                <p>b. Jabatan / Instansi</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 text-left">
                                <p>b. {{ $previewData['pegawai_jabatan'] }}</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 pb-3 text-center">

                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>c. Tingkat Biaya Perjalanan Dinas</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>c. {{ $previewData['tingkat_biaya'] }}</p>
                            </td>
                        </tr>
                        <!-- BAGIAN 3 END -->

                        <tr>
                            <td class="min-w-[10px] border border-navy p-3 text-center">
                                4.
                            </td>
                            <td class="min-w-[250px] border border-navy p-3 text-left">
                                Maksud Perjalanan Dinas
                            </td>
                            <td class="min-w-[300px] border border-navy p-3 text-left">
                                <p class="whitespace-preline">{!! $previewData['maksud_perjalanan'] !!}</p>
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

                        <!-- BAGIAN 6 START -->
                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 pt-3 text-center">
                                6.
                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 pt-3 text-left">
                                <p>a. Tempat Berangkat</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 pt-3 text-left">
                                <p>a. {{ $previewData['tempat_berangkat'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 pb-3 text-center">

                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>b. Tempat Tujuan</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>b. {{ $previewData['tempat_tujuan'] }}, {{ $previewData['regency_name'] }},
                                    {{ $previewData['province_name'] }} </p>
                            </td>
                        </tr>
                        <!-- BAGIAN 6 END -->

                        <!-- BAGIAN 7 START -->
                        <tr>
                            <td class="min-w-[10px] border border-b-0 border-navy px-3 pt-3 text-center">
                                7.
                            </td>
                            <td class="min-w-[250px] border border-b-0 border-navy px-3 pt-3 text-left">
                                <p>a. Lamanya Perjalanan Dinas</p>
                            </td>
                            <td class="min-w-[300px] border border-b-0 border-navy px-3 pt-3 text-left">
                                <p>a. {{ $previewData['lama_perjalanan'] }} Hari</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 text-center">
                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 text-left">
                                <p>b. Tanggal Berangkat</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 text-left">
                                <p>b.
                                    {{ Carbon\Carbon::parse($previewData['tanggal_berangkat'])->isoFormat('dddd, D MMMM Y') }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 pb-3 text-center">
                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>c. Tanggal Harus Kembali</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>c.
                                    {{ Carbon\Carbon::parse($previewData['tanggal_pulang'])->isoFormat('dddd, D MMMM Y') }}
                                </p>
                            </td>
                        </tr>
                        <!-- BAGIAN 7 END -->

                        <!-- BAGIAN 8 START -->
                        <tr>
                            <td class="min-w-[10px] border border-b-0 border-navy px-3 pt-3 text-center">
                                8.
                            </td>
                            <td class="min-w-[250px] border border-b-0 border-navy px-3 pt-3 text-left">
                                <p>Pembebanan Anggaran :</p>
                                <p>a. Instansi</p>
                            </td>
                            <td class="min-w-[300px] border border-b-0 border-navy px-3 pt-3 text-left">
                                <p>&nbsp;</p>
                                <p>a. {{ $previewData['pembebanan_instansi'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="min-w-[10px] border border-y-0 border-navy px-3 pb-3 text-center">
                            </td>
                            <td class="min-w-[250px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>b. Mata Anggaran</p>
                            </td>
                            <td class="min-w-[300px] border border-y-0 border-navy px-3 pb-3 text-left">
                                <p>b. {{ $previewData['kode_rekening'] }} - {{ $previewData['uraian_rekening'] }}</p>
                            </td>
                        </tr>
                        <!-- BAGIAN 8 END -->

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
                            @if ($previewData['issued_nip'] != '1000')
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
                        @if ($previewData['issued_pangkat'] && $previewData['issued_golongan'])
                            <div class="whitespace-nowrap">
                                {{ $previewData['issued_pangkat'] }}
                                ({{ $previewData['issued_golongan'] }})
                            </div>
                        @endif
                        <div class="whitespace-nowrap">
                            @if ($previewData['issued_nip'] != '1000')
                                NIP. {{ $previewData['issued_nip'] }}
                                {{-- @else
                                BUPATI KABUPATEN OGAN ILIR --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-8">
                <hr class="border-navy">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="hidden lg:block">
                </div>
                <div class="col-span-2 lg:col-span-1">

                    <div class="flex items-center gap-2">
                        <div class="flex-none w-[200px]">
                            SPPD No.
                        </div>
                        <div class="grow">
                            : {{ $previewData['nomor_sppd'] }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex-none w-[200px]">
                            Berangkat dari (tempat kedudukan)
                        </div>
                        <div class="grow">
                            : {{ $previewData['tempat_berangkat'] }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex-none w-[200px]">
                            Pada tanggal
                        </div>
                        <div class="grow">
                            :
                            {{ $previewData['tanggal_berangkat'] ? Carbon\Carbon::parse($previewData['tanggal_berangkat'])->isoFormat('D MMMM Y') : '' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex-none w-[200px]">
                            Ke
                        </div>
                        <div class="grow">
                            : {{ $previewData['tempat_tujuan'] }}
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="">
                            Pejabat Pelaksana Teknis Kegiatan
                        </div>
                        <div class="h-[75px]"></div>
                        <div class="">
                            NAMA PPTK
                        </div>
                    </div>

                </div>
            </div>

            <div class="mx-8 my-4">
                <hr class="border-navy-light">
            </div>

            <div class="mx-8 my-4 grid grid-cols-2 gap-4">
                <!-- BAGIAN II START -->
                <div class="col-span-2 lg:col-span-1 flex gap-2 items-start">
                    <div>
                        II.
                    </div>
                    <div class="">
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Tiba di
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Pada tanggal
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Kepala
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-2 lg:col-span-1 flex gap-2 items-start">
                    <div class="">
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Berangkat dari
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Ke
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Pada tanggal
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Kepala
                            </div>
                            <div class="grow">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- BAGIAN II END -->

                <div class="col-span-2">
                    <div class="">
                        <hr class="border-navy-light">
                    </div>
                </div>

                <!-- BAGIAN III START -->
                <div class="col-span-2 lg:col-span-1 flex gap-2 items-start">
                    <div>
                        III.
                    </div>
                    <div class="">
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Tiba di
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Pada tanggal
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Kepala
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-2 lg:col-span-1 flex gap-2 items-start">
                    <div class="">
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Berangkat dari
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Ke
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Pada tanggal
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Kepala
                            </div>
                            <div class="grow">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- BAGIAN III END -->

                <div class="col-span-2">
                    <div class="">
                        <hr class="border-navy-light">
                    </div>
                </div>

                <!-- BAGIAN IV START -->
                <div class="col-span-2 lg:col-span-1 flex gap-2 items-start">
                    <div>
                        IV.
                    </div>
                    <div class="">
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Tiba di
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Pada tanggal
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Kepala
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-2 lg:col-span-1 flex gap-2 items-start">
                    <div class="">
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Berangkat dari
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Ke
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Pada tanggal
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Kepala
                            </div>
                            <div class="grow">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- BAGIAN IV END -->

                <div class="col-span-2">
                    <div class="">
                        <hr class="border-navy-light">
                    </div>
                </div>

                <!-- BAGIAN V START -->
                <div class="col-span-2 lg:col-span-1 flex gap-2 items-start">
                    <div>
                        V.
                    </div>
                    <div class="">
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Tiba di
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-none w-[200px]">
                                Pada tanggal
                            </div>
                            <div class="grow">
                                : .................................
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-2 lg:col-span-1">
                    <div class="">
                        Telah diperiksa, dengan keterangan bahwa tersebut diatas benar dilakukan
                        atas perintahnya dan semata-mata untuk
                        kepentingan jabatan dalam waktu yang
                        sesingkat – singkatnya.
                    </div>

                    <div class="mt-5">
                        <div class="">
                            {{ $previewData['issued_jabatan'] }}
                        </div>
                        <div class="h-[75px]"></div>
                        <div class="">
                            {{ $previewData['issued_name'] }}
                        </div>

                        @if ($previewData['issued_nip'] != '1000')
                            <div class="">
                                {{ $previewData['issued_pangkat'] }}
                            </div>
                            <div class="">
                                NIP. {{ $previewData['issued_nip'] }}
                            </div>
                        @endif

                    </div>
                </div>
                <!-- BAGIAN V END -->

                <div class="col-span-2">
                    <div class="">
                        <hr class="border-navy-light">
                    </div>
                </div>

                <div class="col-span-2">
                    <div>
                        VI. CATATAN LAIN-LAIN
                    </div>
                </div>

                <div class="col-span-2">
                    <div class="">
                        <hr class="border-navy-light">
                    </div>
                </div>

                <div class="col-span-2">
                    <div>
                        VII. PERHATIAN
                    </div>
                    <div class="pl-7">
                        Pejabat yang berwenang menerbitkan SPPD, pegawai yang melakukan perjalanan dinas, para pejabat
                        yang mengesahkan tanggal berangkat/tiba serta Bendaharawan bertanggung jawab berdasarkan
                        peraturan-peraturan Keuangan Negara apabila Negara mendapat rugi akibat kesalahan, kealpaannya.
                    </div>
                </div>

            </div>

        </div>
    @endif
</div>
