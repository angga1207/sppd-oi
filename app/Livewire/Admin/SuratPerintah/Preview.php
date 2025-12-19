<?php

namespace App\Livewire\Admin\SuratPerintah;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\SPPD;
use Livewire\Livewire;
use Livewire\Component;
use App\Models\Employee;
use App\Models\SuratPerintah;
use App\Models\StatusSuratLog;
use Livewire\Attributes\Title;
use PhpOffice\PhpWord\PhpWord;
use App\Services\QrCodeService;
use Livewire\Attributes\Layout;
use App\Services\PhpWordService;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ConvertHtmlListToText;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('components.layouts.app')]
#[Title('Preview Surat')]
class Preview extends Component
{
    use ConvertHtmlListToText;

    public $dataId;
    public $previewData;
    public $prevType = 'spt';
    public $prevSppdKey = 0;
    // loading
    public $isConverting = false;

    public function mount($id)
    {
        if (!$id) {
            return redirect()->route('admin.surat-perintah.index');
        }
        $this->dataId = $id;
        $this->previewData = SuratPerintah::find($id);
    }

    public function downloadSuratPerintah()
    {
        if ($this->previewData->status !== 'approved') {
            LivewireAlert::title('Peringatan!')
                ->text('Surat Perintah Tugas hanya dapat diunduh jika berstatus Disetujui.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }
        $penandaTangan = $this->previewData->publicationEmployee;
        if (!$penandaTangan) {
            LivewireAlert::title('Peringatan!')
                ->text('Penanda tangan surat perintah tidak ditemukan. Silakan atur penanda tangan pada data pegawai.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->previewData->file_pdf_signed && Storage::disk('public')->exists('surat_perintah_tugas_sign/' . $this->previewData->file_pdf_signed)) {
            return Storage::disk('public')->download('surat_perintah_tugas_sign/' . $this->previewData->file_pdf_signed, $this->previewData->file_pdf_signed);
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


    public function confirmSent()
    {
        $data = SuratPerintah::findOrFail($this->dataId);
        if ($data->status == 'sent') {
            LivewireAlert::title('Peringatan')
                ->text('Surat Perintah sedang dalam proses menunggu ditandatangani.')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }
        if ($data->status == 'approved') {
            LivewireAlert::title('Peringatan')
                ->text('Surat Perintah sudah ditandatangani dan tidak dapat dikirim ulang.')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }
        if ($data->status == 'rejected') {
            LivewireAlert::title('Peringatan')
                ->text('Surat Perintah ditolak. Silakan perbaiki data sebelum mengirim ulang.')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        LivewireAlert::title('Konfirmasi Kirim?')
            ->text('Ingin mengirim Surat Perintah Tugas untuk ditandatangani?')
            ->warning()
            ->withConfirmButton('Ya, Kirim!')
            ->withCancelButton('Batal')
            ->onConfirm('preSent', ['id' => $this->dataId])
            ->timer(0)
            ->show();
    }

    public function preSent()
    {
        $this->dispatch('initiateConvert');
    }

    public function sent()
    {
        $data = SuratPerintah::findOrFail($this->dataId);
        if ($data->status != 'draft') {
            LivewireAlert::title('Peringatan')
                ->text('Surat Perintah sudah ditandatangani dan tidak dapat dikirim ulang.')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        $penandaTangan = $this->previewData['employee_giver_id'] ? Employee::find($this->previewData['employee_giver_id']) : null;

        if (!$penandaTangan) {
            LivewireAlert::title('Peringatan!')
                ->text('Penanda tangan surat perintah tidak ditemukan. Silakan atur penanda tangan pada data pegawai.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $kopType = null;
        if ($penandaTangan->instance_id == null) {
            $kopType = 'bupati';
            $templatePath = storage_path('app/templates/spt_kop_bupati.docx');
            $templatePathSPPD = storage_path('app/templates/sppd_kop_bupati.docx');
        } else if ($penandaTangan->instance_id && $penandaTangan->instance_id == 15) {
            // 15 = Sekretariat Daerah
            $kopType = 'sekda';
            $templatePath = storage_path('app/templates/spt_kop_sekda.docx');
            $templatePathSPPD = storage_path('app/templates/sppd_kop_sekda.docx');
        } else {
            $kopType = 'perangkat_daerah';
            $templatePath = storage_path('app/templates/spt_kop_perangkat_daerah.docx');
            $templatePathSPPD = storage_path('app/templates/sppd_kop_perangkat_daerah.docx');
        }

        // check template file exists
        if (!file_exists($templatePath)) {
            // session()->flash('error', 'Template surat perintah tidak ditemukan.');
            LivewireAlert::title('Error!')
                ->text('Template surat perintah tidak ditemukan.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($kopType == 'perangkat_daerah') {
            $fileWord = $this->generateSuratPerintahTugas($templatePath, $penandaTangan, $this->previewData, $penandaTangan->instance);
        } else if ($kopType == 'bupati') {
            $fileWord = $this->generateSuratPerintahTugas($templatePath, $penandaTangan, $this->previewData);
        } else if ($kopType == 'sekda') {
            $fileWord = $this->generateSuratPerintahTugas($templatePath, $penandaTangan, $this->previewData);
        }

        // Get full path untuk file docx
        // $fileWord berisi path seperti: 'storage/surat_perintah_tugas/filename.docx'
        // Kita perlu mengambil hanya bagian setelah 'storage/'
        $relativePath = str_replace('storage/', '', $fileWord);
        $file = Storage::disk('public')->path($relativePath);

        // Debug: pastikan file exists
        if (!file_exists($file)) {
            LivewireAlert::title('Error!')
                ->text('File DOCX tidak ditemukan: ' . $file)
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        // Set directory untuk menyimpan PDF (bukan path lengkap, hanya directory)
        $saveDir = storage_path('app/public/surat_perintah_tugas');

        // Konversi DOCX ke PDF
        $convert = $this->convertDocxtoPdf($file, $saveDir);

        if (!$convert) {
            LivewireAlert::title('Error!')
                ->text('Gagal mengkonversi Surat Perintah Tugas ke PDF.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return; // Stop jika konversi gagal
        }

        // Get nama file PDF hasil konversi
        $fileName = pathinfo($file, PATHINFO_FILENAME) . '.pdf';
        $pdfPath = $saveDir . '/' . $fileName;

        if ($fileName == null || !file_exists($pdfPath)) {
            LivewireAlert::title('Error!')
                ->text('File PDF hasil konversi tidak ditemukan.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }
        $data->file_pdf = $fileName;
        $data->save();

        // SPPD START
        $sppds = SPPD::where('surat_perintah_id', $data->id)->get();
        foreach ($sppds as $sppd) {
            if ($kopType == 'perangkat_daerah') {
                $fileWordSPPD = $this->generateSPPD($templatePathSPPD, $penandaTangan, $sppd, $penandaTangan->instance);
            } else if ($kopType == 'bupati') {
                $fileWordSPPD = $this->generateSPPD($templatePathSPPD, $penandaTangan, $sppd);
            } else if ($kopType == 'sekda') {
                $fileWordSPPD = $this->generateSPPD($templatePathSPPD, $penandaTangan, $sppd);
            }

            $relativePath = str_replace('storage/', '', $fileWordSPPD);
            $file = Storage::disk('public')->path($relativePath);

            // Debug: pastikan file exists
            if (!file_exists($file)) {
                LivewireAlert::title('Error!')
                    ->text('File DOCX SPPD tidak ditemukan: ' . $file)
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return;
            }

            // Set directory untuk menyimpan PDF (bukan path lengkap, hanya directory)
            $saveDir = storage_path('app/public/sppd');

            // Konversi DOCX ke PDF
            $convert = $this->convertDocxtoPdf($file, $saveDir);

            if (!$convert) {
                LivewireAlert::title('Error!')
                    ->text('Gagal mengkonversi file SPPD ke PDF.')
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return; // Stop jika konversi gagal
            }

            // Get nama file PDF hasil konversi
            $fileName = pathinfo($file, PATHINFO_FILENAME) . '.pdf';
            $pdfPath = $saveDir . '/' . $fileName;

            if ($fileName == null || !file_exists($pdfPath)) {
                LivewireAlert::title('Error!')
                    ->text('File PDF hasil konversi tidak ditemukan.')
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return;
            }
            $sppd->file_pdf = $fileName;
            $sppd->save();
        }
        // SPPD END

        DB::beginTransaction();
        try {
            // Create initial status log
            StatusSuratLog::create([
                'type' => 'surat_perintah',
                'reference_id' => $data->id,
                'old_status' => $data->status,
                'new_status' => 'sent',
                'keterangan' => 'Surat Perintah Tugas dikirim untuk ditandatangani',
            ]);

            $data->status = 'sent';
            $data->save();

            // sppd update status too
            $sppds = SPPD::where('surat_perintah_id', $data->id)->get();
            foreach ($sppds as $sppd) {
                // Create initial status log for SPPD
                StatusSuratLog::create([
                    'type' => 'sppd',
                    'reference_id' => $sppd->id,
                    'old_status' => $sppd->status,
                    'new_status' => 'sent',
                    'keterangan' => 'SPPD dikirim untuk ditandatangani bersama Surat Perintah Tugas',
                ]);
                $sppd->status = 'sent';
                $sppd->save();
            }

            DB::commit();
            LivewireAlert::title('Berhasil')
                ->text('Surat Perintah berhasil dikirim untuk ditandatangani.')
                ->position('top-end')
                ->timer(3000)
                ->success()
                ->toast()
                ->show();

            $this->dispatch('finishConverting');
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('Gagal mengirim Surat Perintah: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->toast()
                ->error()
                ->show();
            $this->dispatch('finishConverting');
            return;
        }
    }

    private function convertDocxtoPdf($input_file, $output_dir)
    {
        // Pastikan input file exists
        if (!file_exists($input_file)) {
            Log::error('PDF Conversion Failed - File not found', [
                'input_file' => $input_file
            ]);

            LivewireAlert::title('Error!')
                ->text('File input tidak ditemukan.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();

            return false;
        }

        // Pastikan output directory exists
        if (!file_exists($output_dir)) {
            mkdir($output_dir, 0777, true);
        }

        // Escape shell arguments untuk keamanan
        $path = escapeshellarg($input_file);
        $savePath = escapeshellarg($output_dir);

        // LibreOffice shell command to convert doc to pdf
        // $command = "/Applications/LibreOffice.app/Contents/MacOS/soffice --headless --convert-to pdf {$path} --outdir {$savePath} 2>&1";

        // Linux LibreOffice shell command to convert doc to pdf
        $command = "libreoffice --headless --convert-to pdf {$path} --outdir {$savePath} 2>&1";
        // $command = "/opt/libreoffice25.8/program/soffice --headless --convert-to pdf {$path} --outdir {$savePath} 2>&1";

        // COMMAND DARI THEDA
        // $command = "libreoffice7.6 --headless --convert-to pdf $path --outdir $savePath";
        // $command = "/Applications/LibreOffice.app/Contents/MacOS/soffice --headless --convert-to pdf $path --outdir $savePath";


        // Execute the command
        exec($command, $output, $returnVar);

        // Log hasil konversi
        if ($returnVar !== 0) {
            Log::error('PDF Conversion Failed', [
                'command' => $command,
                'input_file' => $input_file,
                'output_dir' => $output_dir,
                'return_code' => $returnVar,
                'output' => implode("\n", $output)
            ]);

            LivewireAlert::title('Error!')
                ->text('Gagal mengkonversi file ke PDF: ' . implode(', ', $output))
                ->error()
                ->toast()
                ->position('top-end')
                ->show();

            return false;
        }

        Log::info('PDF Conversion Success', [
            'input' => $input_file,
            'output_dir' => $output_dir,
            'result' => implode("\n", $output)
        ]);

        return true;
    }

    function generateSuratPerintahTugas($templatePath, $penandaTangan, $previewData, $instance = null)
    {
        $templateProcessor = new TemplateProcessor($templatePath);
        if ($instance) {
            $templateProcessor->setValue('INSTANSI', $instance->name ?? '-');
            $templateProcessor->setValue('alamat', $instance->address ?? '-');
            $templateProcessor->setValue('telp', $instance->phone ?? '-');
            $templateProcessor->setValue('faximile', $instance->fax ?? '-');
            $templateProcessor->setValue('kode_pos', $instance->kode_pos ?? '-');
            $templateProcessor->setValue('email_pos', $instance->email ?? '-');
            $templateProcessor->setValue('website', $instance->website ?? '-');
        }

        $templateProcessor->setValue('nomor_surat', $previewData['nomor_surat'] ?? '-');

        // Convert HTML lists to Word-compatible format
        $dasar = $this->ConvertHtmlListToText($previewData['dasar'] ?? '');
        $tujuan = $this->ConvertHtmlListToText($previewData['tujuan'] ?? '');
        $templateProcessor->setValue('dasar', $dasar);
        $templateProcessor->setValue('untuk', $tujuan);
        $templateProcessor->setValue('tanggal_surat', $previewData['publication_date'] ? Carbon::parse($previewData['publication_date'])->isoFormat('D MMMM Y') : '-');

        $templateProcessor->setValue('nama_penandatangan', $penandaTangan['nama_lengkap'] ?? '-');
        $templateProcessor->setValue('nip_penandatangan', '');
        $templateProcessor->setValue('jabatan_penandatangan', $penandaTangan['jabatan'] ?? '-');

        // QR Code
        // if !$instance == bupati
        if (!$instance) {
            $qrCodeUrl = $this->generateQrCodeSPT('spt', 'bupati', $previewData);
        } else {
            $qrCodeUrl = $this->generateQrCodeSPT('spt', 'perangkat_daerah', $previewData);
        }
        // dd($qrCodeUrl);
        if ($qrCodeUrl) {
            $templateProcessor->setImageValue('qr_code', [
                'path' => $qrCodeUrl,
                'width' => 70,
                'height' => 70,
            ]);
        } else {
            $templateProcessor->setValue('qr_code', ''); // Clear if QR code generation failed
        }

        $sppds = [];
        $arrSppds = SPPD::where('surat_perintah_id', $previewData['id'])
            ->oldest()
            ->get();
        foreach ($arrSppds as $key => $sppd) {
            $sppds[] = [
                'no' => $key + 1,
                'sppd_nama' => $sppd->employeeExecutor->nama_lengkap,
                'sppd_nip' => $sppd->employeeExecutor->nip,
                'sppd_pangkat_golongan' => $sppd->employeeExecutor->pangkat . '(' . $sppd->employeeExecutor->golongan . ')',
                'sppd_jabatan' => $sppd->employeeExecutor->jabatan,
            ];
        }

        $templateProcessor->cloneRowAndSetValues('no', $sppds);

        // save path
        $savePath = storage_path('app/public/surat_perintah_tugas');
        if (!file_exists($savePath)) {
            mkdir($savePath, 0777, true);
        }

        // save to temp file
        $fileName = 'Surat_Perintah_Tugas_' . ($previewData['nomor_surat'] ? str()->replace('/', '_', $previewData['nomor_surat']) . '.docx' : 'Surat_Perintah_Tugas.docx');
        $tempFilePath = $savePath . '/' . $fileName;
        $templateProcessor->saveAs($tempFilePath);

        // save in to database
        $dataSuratPerintah = SuratPerintah::find($previewData['id']);
        $dataSuratPerintah->file_word = $fileName;
        $dataSuratPerintah->save();

        $downloadFile = 'storage/surat_perintah_tugas/' . $fileName;
        return $downloadFile;
    }

    function generateSPPD($templatePath, $penandaTangan, $DataSPPD, $instance = null)
    {
        // dd($DataSPPD, $instance);
        $templateProcessor = new TemplateProcessor($templatePath);
        if ($instance) {
            $templateProcessor->setValue('INSTANSI', $instance->name ?? '-');
            $templateProcessor->setValue('alamat', $instance->address ?? '-');
            $templateProcessor->setValue('telp', $instance->phone ?? '-');
            $templateProcessor->setValue('faximile', $instance->fax ?? '-');
            $templateProcessor->setValue('kode_pos', $instance->kode_pos ?? '-');
            $templateProcessor->setValue('email_pos', $instance->email ?? '-');
            $templateProcessor->setValue('website', $instance->website ?? '-');
        }

        $templateProcessor->setValue('nomor_surat', $DataSPPD['nomor_sppd'] ?? '-');
        $templateProcessor->setValue('nomor_surat_perintah', $DataSPPD->suratPerintah['nomor_surat'] ?? '-');

        $templateProcessor->setValue('pegawai_name', $DataSPPD->employeeExecutor['nama_lengkap'] ?? '-');
        $templateProcessor->setValue('pegawai_nip', $DataSPPD->employeeExecutor['nip'] ?? '-');

        $templateProcessor->setValue('pegawai_pangkat', $DataSPPD->employeeExecutor['pangkat'] ?? '-');
        $templateProcessor->setValue('pegawai_jabatan', $DataSPPD->employeeExecutor['jabatan'] ?? '-');
        $templateProcessor->setValue('tingkat_biaya', collect(SPPD::GetTingkatOptions())->firstWhere('value', $DataSPPD->tingkat_biaya)['label'] ?? '');

        $maksudPerjalananDinas = $this->ConvertHtmlListToText($DataSPPD['maksud_perjalanan'] ?? '');
        $templateProcessor->setValue('maksud_perjalanan', $maksudPerjalananDinas);

        $templateProcessor->setValue('alat_angkutan', $DataSPPD['alat_angkutan'] ?? '-');
        $templateProcessor->setValue('tempat_berangkat', $DataSPPD['tempat_berangkat'] ?? '-');
        $templateProcessor->setValue('tempat_tujuan', $DataSPPD['tempat_tujuan'] . ', ' . $DataSPPD->regency->name . ', ' . $DataSPPD->province->name);
        $templateProcessor->setValue('lama_perjalanan', $DataSPPD['lama_perjalanan'] ?? '-');
        $templateProcessor->setValue('tanggal_berangkat', $DataSPPD['tanggal_berangkat'] ? Carbon::parse($DataSPPD['tanggal_berangkat'])->isoFormat('D MMMM Y') : '-');
        $templateProcessor->setValue('tanggal_pulang', $DataSPPD['tanggal_pulang'] ? Carbon::parse($DataSPPD['tanggal_pulang'])->isoFormat('D MMMM Y') : '-');
        $templateProcessor->setValue('pembebanan_instansi', $DataSPPD->instancePembebanan['name'] ?? '-');
        $templateProcessor->setValue('kode_rekening', $DataSPPD['kode_rekening'] ?? '-');
        $templateProcessor->setValue('uraian_rekening', $DataSPPD['uraian_rekening'] ?? '-');
        $templateProcessor->setValue('keterangan_lain', $DataSPPD['keterangan_lain'] ?? '-');

        // Convert HTML lists to Word-compatible format
        // $dasar = $this->ConvertHtmlListToText($DataSPPD['dasar'] ?? '');

        // $templateProcessor->setValue('dasar', $dasar);

        // $templateProcessor->setValue('tanggal_surat', $DataSPPD['publication_date'] ? Carbon::parse($DataSPPD['publication_date'])->isoFormat('D MMMM Y') : '-');
        $templateProcessor->setValue('tanggal_surat', $DataSPPD['publication_date'] ?? '-');

        $templateProcessor->setValue('nama_penandatangan', $penandaTangan->nama_lengkap ?? '-');
        $templateProcessor->setValue('nip_penandatangan', $penandaTangan->nip ?? '-');
        $templateProcessor->setValue('jabatan_penandatangan', $penandaTangan->jabatan ?? '-');

        // QR Code
        if (!$instance) {
            $qrCodeUrl = $this->generateQrCodeSPPD('sppd', 'bupati', $DataSPPD);
        } else {
            $qrCodeUrl = $this->generateQrCodeSPPD('sppd', 'perangkat_daerah', $DataSPPD);
        }
        if ($qrCodeUrl) {
            $templateProcessor->setImageValue('qr_code', [
                'path' => $qrCodeUrl,
                'width' => 70,
                'height' => 70,
            ]);
        } else {
            $templateProcessor->setValue('qr_code', ''); // Clear if QR code generation failed
        }

        // save path
        $savePath = storage_path('app/public/sppd');
        if (!file_exists($savePath)) {
            mkdir($savePath, 0777, true);
        }

        // save to temp file
        $fileName = 'Surat_Perintah_Perjalanan_Dinas_' . ($DataSPPD['nomor_sppd'] ? str()->replace('/', '_', $DataSPPD['nomor_sppd']) . '.docx' : 'Surat_Perintah_Perjalanan_Dinas_.docx');
        $tempFilePath = $savePath . '/' . $fileName;
        $templateProcessor->saveAs($tempFilePath);

        // save in to database
        $dataSPPD = SPPD::find($DataSPPD['id']);
        $dataSPPD->file_word = $fileName;
        $dataSPPD->save();

        $downloadFile = 'storage/sppd/' . $fileName;
        return $downloadFile;
    }

    public function generateQrCodeSPT($type, $kopType, $DataSPT)
    {
        try {
            $route = route('scan.spt', ['id' => $DataSPT['uuid']]);

            if ($type != 'spt') {
                // throw new \Exception('Tipe QR Code tidak dikenali.');
                LivewireAlert::title('Error!')
                    ->text('Tipe QR Code tidak dikenali.')
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return null;
            }

            if ($kopType == 'bupati') {
                // For Bupati, use specific logo
                $logoOI = public_path('assets/logo-pancasila.png');
            } else {
                $logoOI = public_path('assets/logo-oi.png');
            }

            $qrCode = QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H') // High error correction for logo overlay
                ->color(0, 0, 0)      // Black foreground
                ->backgroundColor(255, 255, 255) // White background
                ->merge($logoOI, 0.3, true) // 0.15 = 15% of QR size, true = center positioning
                ->generate($route);

            // Save to storage/surat_perintah_tugas/qrcodes/
            $savePath = storage_path('app/public/surat_perintah_tugas/qrcodes');
            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $fileName = 'QR_Code_SPT_' . $DataSPT['uuid'] . '.png';
            $filePath = 'surat_perintah_tugas/qrcodes/' . $fileName;

            // Store the QR code
            Storage::disk('public')->put($filePath, $qrCode);

            // $qrCodeUrl = asset('storage/' . $filePath);
            $qrCodeUrl = storage_path('app/public/' . $filePath);

            return $qrCodeUrl;
        } catch (\Exception $e) {
            Log::error('Failed to generate QR Code', [
                'error' => $e->getMessage(),
                'surat_perintah_id' => $DataSPT['id']
            ]);

            LivewireAlert::title('Error!')
                ->text('Gagal membuat QR Code: ' . $e->getMessage())
                ->error()
                ->toast()
                ->position('top-end')
                ->show();

            return null;
        }
    }

    public function generateQrCodeSPPD($type, $kopType, $dataSPPD)
    {
        try {
            $route = route('scan.sppd', ['id' => $dataSPPD['uuid']]);

            if ($type != 'sppd') {
                // throw new \Exception('Tipe QR Code tidak dikenali.');
                LivewireAlert::title('Error!')
                    ->text('Tipe QR Code tidak dikenali.')
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return null;
            }

            if ($kopType == 'bupati') {
                // For Bupati, use specific logo
                $logoOI = public_path('assets/logo-pancasila.png');
            } else {
                $logoOI = public_path('assets/logo-oi.png');
            }

            $qrCode = QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H') // High error correction for logo overlay
                ->color(0, 0, 0)      // Black foreground
                ->backgroundColor(255, 255, 255) // White background
                ->merge($logoOI, 0.3, true) // 0.15 = 15% of QR size, true = center positioning
                ->generate($route);

            // Save to storage/sppd/qrcodes/
            $savePath = storage_path('app/public/sppd/qrcodes');
            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $fileName = 'QR_Code_SPPD_' . $dataSPPD['uuid'] . '.png';
            $filePath = 'sppd/qrcodes/' . $fileName;

            // Store the QR code
            Storage::disk('public')->put($filePath, $qrCode);

            // $qrCodeUrl = asset('storage/' . $filePath);
            $qrCodeUrl = storage_path('app/public/' . $filePath);

            return $qrCodeUrl;
        } catch (\Exception $e) {
            Log::error('Failed to generate QR Code', [
                'error' => $e->getMessage(),
                'surat_perintah_id' => $dataSPPD['id']
            ]);

            LivewireAlert::title('Error!')
                ->text('Gagal membuat QR Code: ' . $e->getMessage())
                ->error()
                ->toast()
                ->position('top-end')
                ->show();

            return null;
        }
    }

    public function render()
    {
        return view('livewire.admin.surat-perintah.preview');
    }
}
