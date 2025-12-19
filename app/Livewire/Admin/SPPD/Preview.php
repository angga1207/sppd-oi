<?php

namespace App\Livewire\Admin\SPPD;

use Carbon\Carbon;
use App\Models\SPPD;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Preview extends Component
{
    // Preview Data
    public $previewData = [];
    public $suratPerintahId;

    public function mount($id)
    {
        // Fetch SPPD data based on the provided ID
        $sppd = \App\Models\SPPD::with(['employeeGiver', 'employeeExecutor', 'instance', 'publicationEmployee'])->findOrFail($id);
        $this->suratPerintahId = $sppd->surat_perintah_id;

        // dd($sppd);
        // Prepare data for preview
        $this->previewData = [
            'id' => $sppd->id,
            'uuid' => $sppd->uuid,
            'nomor_sppd' => $sppd->nomor_sppd,
            'nomor_surat_perintah' => $sppd->suratPerintah ? $sppd->suratPerintah->nomor_surat : '',

            'pejabat_name' => $sppd->employeeGiver ? $sppd->employeeGiver->nama_lengkap : '',
            'pejabat_nip' => $sppd->employeeGiver ? $sppd->employeeGiver->nip : '',
            'pejabat_pangkat' => $sppd->employeeGiver ? $sppd->employeeGiver->pangkat : '',
            'pejabat_golongan' => $sppd->employeeGiver ? $sppd->employeeGiver->golongan : '',
            'pejabat_jabatan' => $sppd->employeeGiver ? $sppd->employeeGiver->jabatan : '',
            'pejabat_instance_name' => $sppd->employeeGiver && $sppd->employeeGiver->instance ? $sppd->employeeGiver->instance->name : '',

            'pegawai_name' => $sppd->employeeExecutor ? $sppd->employeeExecutor->nama_lengkap : '',
            'pegawai_nip' => $sppd->employeeExecutor ? $sppd->employeeExecutor->nip : '',
            'pegawai_pangkat' => $sppd->employeeExecutor ? $sppd->employeeExecutor->pangkat : '',
            'pegawai_golongan' => $sppd->employeeExecutor ? $sppd->employeeExecutor->golongan : '',
            'pegawai_jabatan' => $sppd->employeeExecutor ? $sppd->employeeExecutor->jabatan : '',
            'pegawai_instance_name' => $sppd->employeeExecutor && $sppd->employeeExecutor->instance ? $sppd->employeeExecutor->instance->name : '',

            // 'tingkat_biaya' => $sppd->tingkat_biaya,
            'tingkat_biaya' => collect(SPPD::GetTingkatOptions())->firstWhere('value', $sppd->tingkat_biaya)['label'] ?? '',
            'maksud_perjalanan' => $sppd->maksud_perjalanan,
            'alat_angkutan' => $sppd->alat_angkutan,
            'tempat_berangkat' => $sppd->tempat_berangkat,
            'province_name' => $sppd->province ? str()->title($sppd->province->name) : '',
            'regency_name' => $sppd->regency ? str()->title($sppd->regency->name) : '',
            'tempat_tujuan' => $sppd->tempat_tujuan,

            'lama_perjalanan' => $sppd->lama_perjalanan,
            // 'tanggal_berangkat' => Carbon::parse($sppd->tanggal_berangkat)->isoFormat('D MMMM Y'),
            // 'tanggal_pulang' => Carbon::parse($sppd->tanggal_pulang)->isoFormat('D MMMM Y'),
            'tanggal_berangkat' => $sppd->tanggal_berangkat,
            'tanggal_pulang' => $sppd->tanggal_pulang,

            'pembebanan_instansi' => $sppd->instancePembebanan ? $sppd->instancePembebanan->name : '',
            'kode_sub_kegiatan' => $sppd->kode_sub_kegiatan,
            'uraian_sub_kegiatan' => $sppd->uraian_sub_kegiatan,
            'anggaran_sub_kegiatan' => number_format($sppd->anggaran_sub_kegiatan, 2, ',', '.'),

            'kode_rekening' => $sppd->kode_rekening,
            'uraian_rekening' => $sppd->uraian_rekening,
            'anggaran_rekening' => number_format($sppd->anggaran_rekening, 2, ',', '.'),
            'keterangan_lain' => $sppd->keterangan_lain,

            'publication_place' => $sppd->publication_place,
            'publication_date' => Carbon::parse($sppd->publication_date)->isoFormat('D MMMM Y'),
            'publicationEmployee' => $sppd->publicationEmployee,
            // 'publicationEmployee_id' => $sppd->publicationEmployee_id,

            'status' => $sppd->status,

            'issued_name' => $sppd->publicationEmployee ? $sppd->publicationEmployee->nama_lengkap : '',
            'issued_nip' => $sppd->publicationEmployee ? $sppd->publicationEmployee->nip : '',
            'issued_pangkat' => $sppd->publicationEmployee ? $sppd->publicationEmployee->pangkat : '',
            'issued_golongan' => $sppd->publicationEmployee ? $sppd->publicationEmployee->golongan : '',
            'issued_jabatan' => $sppd->publicationEmployee ? $sppd->publicationEmployee->jabatan : '',
            'issued_jabatan_title' => $sppd->publicationEmployee ? (str_contains(strtolower($sppd->publicationEmployee->jabatan), 'kepala dinas') ? 'KEPALA DINAS' : '') : '',
            'issued_instance_name' => $sppd->publicationEmployee && $sppd->publicationEmployee->instance ? $sppd->publicationEmployee->instance->name : '',

            'file_word' => $sppd->file_word,
            'file_pdf' => $sppd->file_pdf,
            'file_pdf_signed' => $sppd->file_pdf_signed,
        ];

        // dd($this->previewData);
    }

    public function downloadSuratPerintah()
    {
        if ($this->previewData['status'] !== 'approved') {
            LivewireAlert::title('Peringatan!')
                ->text('Surat Perintah Tugas hanya dapat diunduh jika berstatus Disetujui.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }
        $penandaTangan = $this->previewData['publicationEmployee'];
        if (!$penandaTangan) {
            LivewireAlert::title('Peringatan!')
                ->text('Penanda tangan surat perintah tidak ditemukan. Silakan atur penanda tangan pada data pegawai.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->previewData['file_pdf_signed'] && Storage::disk('public')->exists('sppd_sign/' . $this->previewData['file_pdf_signed'])) {
            return Storage::disk('public')->download('sppd_sign/' . $this->previewData['file_pdf_signed'], $this->previewData['file_pdf_signed']);
        } else {
            LivewireAlert::title('Peringatan!')
                ->text('File Surat Perintah Tugas yang sudah ditandatangani tidak ditemukan.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.s-p-p-d.preview');
    }
}
