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
                            class="tab-button-active min-w-fit flex-grow justify-center">
                            <x-heroicon-o-photo class="w-5 h-5 currentColor" />
                            <span class="hidden sm:inline">Preview</span>
                            <span class="sm:hidden">Preview</span>
                        </a>

                        <!-- Tab 4: Logs -->
                        <a href="{{ route('admin.surat-perintah.logs', ['id' => $dataId]) }}"
                            class="tab-button min-w-fit flex-grow justify-center">
                            <x-heroicon-m-queue-list class="w-5 h-5 currentColor" />
                            <span class="hidden sm:inline">Log</span>
                            <span class="sm:hidden">Log</span>
                        </a>
                    </nav>
                </div>
            </div>

            @if ($previewData->sppds->count() > 0)
                <div class="">
                    <!-- Select option for SPT and SPPD -->
                    <div class="p-6 pb-0 flex items-center justify-between flex-col sm:flex-row gap-4">
                        {{-- <h3 class="text-xl font-bold text-navy flex items-center gap-2">
                            Pilih Jenis Preview Surat
                        </h3> --}}
                        <div class="grow flex items-center flex-wrap gap-2">
                            <button type="button" wire:click="$set('prevType', 'spt')"
                                class="btn-sm whitespace-nowrap {{ $prevType == 'spt' ? 'btn-primary' : 'btn-secondary' }}">
                                <x-heroicon-o-photo class="w-4 h-4 mr-1" />
                                Preview SPT
                            </button>
                            <button type="button" wire:click="$set('prevType', 'sppd')"
                                class="btn-sm whitespace-nowrap {{ $prevType == 'sppd' ? 'btn-primary' : 'btn-secondary' }}">
                                <x-heroicon-o-document-text class="w-4 h-4 mr-1" />
                                Preview SPPD
                            </button>

                        </div>
                        @if ($prevType == 'sppd')
                            <div class="">
                                <select class="form-input min-w-[250px]" wire:model.live="prevSppdKey">
                                    @foreach ($previewData->sppds as $key => $sppd)
                                        <option value="{{ $key }}">{{ $sppd->nomor_sppd }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($prevType == 'spt')
                <div class="p-6 pt-0">
                    <div class="flex sm:items-center sm:justify-between flex-col sm:flex-row gap-2 p-4">
                        <h3 class="text-xl font-bold text-navy flex items-center gap-2">
                            Preview Surat Perintah Tugas
                        </h3>

                        @if ($previewData->status === 'approved')
                            <div class="flex gap-2 flex-wrap">
                                <button type="button" class="btn-sm btn-success" wire:click="downloadSuratPerintah">
                                    <x-heroicon-o-cloud-arrow-down class="w-4 h-4 mr-1" />
                                    Unduh Surat Perintah Tugas
                                </button>
                            </div>
                        @endif
                    </div>

                    @if ($previewData->status === 'approved')
                        {{-- <div
                            class="mb-4 p-4 bg-green-100 border border-green-300 rounded-lg text-green-800 flex items-center gap-2">
                            <x-heroicon-o-check-circle class="w-6 h-6" />
                            Surat Perintah Tugas ini telah ditandatangani secara digital dan dapat diunduh.
                        </div> --}}
                        <div class="mt-4 border border-navy rounded-xl p-4 pb-10 bg-white shadow-lg relative">
                            <iframe
                                src="{{ asset('storage/surat_perintah_tugas_sign/' . $previewData['file_pdf_signed']) }}"
                                class="w-full h-[800px] rounded-xl border border-navy"></iframe>
                        </div>
                    @else
                        <div
                            class="mt-4 border border-navy rounded-xl p-4 pb-10 bg-white shadow-lg relative select-none">
                            <div
                                class="w-full h-full absolute top-0 left-0 bg-slate-400/20 rounded-xl flex items-center justify-center text-navy/50 text-4xl font-bold z-10 select-none pointer-events-none">
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

                                    @if ($previewData->sppds->count() > 0)
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
                                                    @foreach ($previewData->sppds as $sppd)
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
                                                                                @if ($sppd->employeeExecutor->pangkat && $sppd->employeeExecutor->golongan)
                                                                                    {{ $sppd->employeeExecutor->pangkat }}
                                                                                    ({{ $sppd->employeeExecutor->golongan }})
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
                                                    {{ Carbon\Carbon::parse($previewData['publication_date'])->isoFormat('D MMMM Y') ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="text-center">
                                        <div class="text-lg font-bold mt-4 whitespace-nowrap">
                                            @if ($previewData->publicationEmployee->nip != '1000')
                                                {{ $previewData->publicationEmployee->jabatan ? (str_contains(strtolower($previewData->publicationEmployee->jabatan), 'kepala dinas') ? 'KEPALA DINAS' : '') : '' }}
                                            @elseif($previewData->publicationEmployee->nip == '1000')
                                                BUPATI OGAN ILIR
                                            @endif
                                        </div>
                                        <div class="h-[75px]">

                                        </div>
                                        <div class="whitespace-nowrap font-bold">
                                            {{ $previewData->publicationEmployee->nama_lengkap ?? 'N/A' }}
                                        </div>
                                        @if ($previewData->publicationEmployee->pangkat && $previewData->publicationEmployee->golongan)
                                            <div class="whitespace-nowrap">
                                                {{ $previewData->publicationEmployee->pangkat }}
                                                ({{ $previewData->publicationEmployee->golongan }})
                                            </div>
                                        @endif
                                        <div class="whitespace-nowrap">
                                            @if ($previewData->publicationEmployee->nip != '1000')
                                                NIP. {{ $previewData->publicationEmployee->nip }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @elseif($prevType == 'sppd')
                <div class="p-6 pt-0">
                    @foreach ($previewData->sppds as $index => $sppd)
                        @if ($index == $prevSppdKey)
                            @livewire('admin.s-p-p-d.preview', ['id' => $sppd->id], key('sppd-preview-' . $sppd->id))
                        @endif
                    @endforeach
                </div>
            @endif

            <div class="p-6 flex flex-col sm:flex-row gap-4 sm:gap-0 items-center justify-between">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.surat-perintah.index') }}" class="btn-secondary">
                        <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
                        Kembali
                    </a>
                    {{-- status --}}
                    @if ($previewData['status'] == 'draft')
                        <div class="text-primary font-bold px-3 py-2 rounded-full flex items-center gap-1">
                            Status :
                            Draft
                            <x-heroicon-o-hand-raised class="w-5 h-5 mr-1" />
                        </div>
                    @elseif($previewData['status'] == 'sent')
                        <div class="text-warning font-bold px-3 py-2 rounded-full flex items-center gap-1">
                            Status :
                            Menunggu Tanda Tangan
                            <x-heroicon-o-clock class="w-5 h-5 mr-1" />
                        </div>
                    @elseif($previewData['status'] == 'approved')
                        <div class="text-success font-bold px-3 py-2 rounded-full flex items-center gap-1">
                            Status :
                            Ditandatangani
                            <x-heroicon-o-finger-print class="w-5 h-5 mr-1" />
                        </div>
                    @endif

                </div>

                <div class="flex items-center gap-2">

                    @if ($previewData['status'] == 'draft')
                        <button type="button" wire:click="confirmSent()" wire:loading.attr="disabled"
                            @if ($previewData->status != 'draft') disabled @endif class="btn-success">
                            <span wire:loading.remove wire:target="confirmSent()" class="flex items-center gap-1">
                                <x-heroicon-o-paper-airplane class="w-4 h-4 mr-1" />
                                Kirim untuk Ditandatangani
                            </span>
                            <span wire:loading wire:target="confirmSent()">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>


    @if ($isConverting)
        <div
            class="fixed z-[200] bg-black w-screen h-screen top-0 left-0 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                <svg class="animate-spin h-8 w-8 text-navy" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <div class="text-lg font-medium text-navy">
                    Sedang memproses dokumen, mohon tunggu...
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        Livewire.on('initiateConvert', () => {
            $wire.set('isConverting', true);
            setTimeout(() => {
                $wire.call('sent');
            }, 500);
        });

        Livewire.on('finishConverting', () => {
            $wire.set('isConverting', false);
            $wire.$refresh()
            // $wire.call('mount', {{ $previewData->id }});
            // setInterval(() => {
            //     $wire.$refresh()
            // }, 500)
        });
    </script>
    <script>
        // Handle copy to clipboard event from Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('copy-to-clipboard', (event) => {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(event.text).then(() => {
                        console.log('Text copied to clipboard');
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                    });
                } else {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = event.text;
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                    } catch (err) {
                        console.error('Fallback: Failed to copy', err);
                    }
                    document.body.removeChild(textArea);
                }
            });
        });
    </script>
@endscript
