<div class="min-h-screen bg-light py-8">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Preview Surat Perintah Tugas
                </h2>
                <p class="mt-2 text-sm text-muted">Preview Surat Perintah Tugas</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.surat-perintah.index') }}" class="btn-secondary">
                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-1" />
                    Daftar Surat Perintah Tugas
                </a>
                <a href="{{ route('admin.surat-perintah.edit', ['id' => $dataId]) }}" class="btn-primary">
                    <x-heroicon-o-pencil-square class="w-5 h-5 mr-1" />
                    Edit Surat Perintah Tugas
                </a>
            </div>
        </div>


        <div class="card p-6">
            <h3 class="text-xl font-bold text-navy flex items-center gap-2 p-4">
                Preview Surat Perintah Tugas
            </h3>

            <div class="mt-4 border border-navy rounded-xl p-4 pb-10 bg-white shadow-lg relative select-none">
                <div
                    class="w-full h-full absolute top-0 left-0 bg-slate-400/20 flex items-center justify-center text-navy/50 text-4xl font-bold z-10 select-none pointer-events-none">
                    <span class="transform -rotate-45 text-center">PREVIEW SURAT PERINTAH TUGAS</span>
                </div>
                <h3 class="text-center m-0 underline text-2xl font-semibold">
                    SURAT PERINTAH TUGAS
                </h3>
                <p class="uppercase text-center m-0 font-semibold text-xl">
                    NOMOR : {{ $previewData['nomor_surat'] }}
                </p>


                <table class="w-full">
                    <tbody class="w-full !align-top">

                        <tr>
                            <td class="w-[150px] p-4">
                                Dasar
                            </td>
                            <td class="!w-[1px] !max-w-[1px] px-1 py-4">
                                :
                            </td>
                            <td class="min-w-[300px] p-4">
                                {!! $previewData['dasar'] !!}
                            </td>
                        </tr>

                        @if($previewData->sppds->count() > 0)
                        <tr>
                            <td colspan="3" class="p-4 text-center">
                                MEMERINTAHKAN
                            </td>
                        </tr>
                        <tr>
                            <td class="p-4">
                                Kepada
                            </td>
                            <td class="!w-[1px] !max-w-[1px] px-1 py-4">
                                :
                            </td>
                            <td class="p-4">
                                <ul class="list-decimal list-inside">
                                    @foreach($previewData->sppds as $sppd)
                                    <li class="flex items-start gap-2 mb-8">
                                        <div>
                                            <table>
                                                <tbody>

                                                    <tr>
                                                        <td class="w-[100px]">
                                                            Nama
                                                        </td>
                                                        <td class="w-[0px] px-2">
                                                            :
                                                        </td>
                                                        <td>
                                                            {{ $sppd->employeeExecutor->nama_lengkap ?? 'N/A' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            NIP
                                                        </td>
                                                        <td class="px-2">
                                                            :
                                                        </td>
                                                        <td>
                                                            {{ $sppd->employeeExecutor->nip ?? 'N/A' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Pangkat/Gol
                                                        </td>
                                                        <td class="px-2">
                                                            :
                                                        </td>
                                                        <td>
                                                            @if($sppd->employeeExecutor->pangkat &&
                                                            $sppd->employeeExecutor->golongan)
                                                            {{ $sppd->employeeExecutor->pangkat }} ({{
                                                            $sppd->employeeExecutor->golongan }})
                                                            @else
                                                            N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Jabatan
                                                        </td>
                                                        <td class="px-2">
                                                            :
                                                        </td>
                                                        <td>
                                                            {{ $sppd->employeeExecutor->jabatan ?? 'N/A' }}
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td class="w-[150px] p-4">
                                Untuk
                            </td>
                            <td class="!w-[1px] !max-w-[1px] px-1 py-4">
                                :
                            </td>
                            <td class="min-w-[300px] p-4">
                                {!! $previewData['tujuan'] !!}
                            </td>
                        </tr>

                    </tbody>
                </table>


                <div class="mt-8 flex items-center justify-center sm:justify-end">
                    <div class="flex flex-col items-center">
                        <table>
                            <tbody>
                                <tr class="whitespace-nowrap">
                                    <td>
                                        Ditetapkan di
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
                                        {{ Carbon\Carbon::parse($previewData['publication_date'])->isoFormat('D MMMM Y')
                                        ?? 'N/A' }}
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
                                {{ $previewData->employeeGiver->nama_lengkap ?? 'N/A' }}
                            </div>
                            @if($previewData->employeeGiver->pangkat && $previewData->employeeGiver->golongan)
                            <div class="whitespace-nowrap">
                                {{ $previewData->employeeGiver->pangkat }}
                                ({{ $previewData->employeeGiver->golongan }})
                            </div>
                            @endif
                            <div class="whitespace-nowrap">
                                @if($previewData->employeeGiver->nip != '1000')
                                NIP : {{ $previewData->employeeGiver->nip }}
                                @else
                                BUPATI KABUPATEN OGAN ILIR
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
