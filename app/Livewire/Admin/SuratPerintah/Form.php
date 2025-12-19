<?php

namespace App\Livewire\Admin\SuratPerintah;

use Carbon\Carbon;
use App\Models\SPPD;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Instance;
use App\Models\SuratPerintah;
use App\Models\StatusSuratLog;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\KlasifikasiNomorSurat;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


#[Layout('components.layouts.app')]
#[Title('Form Surat')]
class Form extends Component
{
    public $dataId;
    public $isEdit = false;
    public $isViewOnly = false;
    public $currentTab = 'input';
    public $isDisabledInstancesGiver = false;
    public $isDisabledInstances = false;

    public $availableYears;
    public $availableMonths;
    public $selectedYear;
    public $selectedMonth;

    public $semestaUsers = [];
    public $rawSemestaUsers = [];
    public $selectedEmployee = null;
    public $semestaOfficers = []; // For commanding officers
    public $rawSemestaOfficers = []; // Raw data for search
    public $commanding_officer = null;
    public $selectedOfficer = null;
    public $selectedSigner = null;
    public $showOfficers = false;
    public $showEmployees = false;
    public $instances = [];
    public $selectedInstanceGiver = null;

    public $isWithSppd = false;

    // Options
    public $alatAngkutOptions = [
        'Kendaraan Dinas',
        'Kendaraan Pribadi',
        'Kendaraan Umum',
    ];
    public $tingkatOptions = [
        [
            'value' => 'A',
            'label' => 'Tingkat A - Untuk Bupati, Wakil Bupati, dan Pimpinan DPRD',
        ],
        [
            'value' => 'B',
            'label' => 'Tingkat B - Untuk Pejabat Esselon II dan Anggota DPRD',
        ],
        [
            'value' => 'C',
            'label' => 'Tingkat C - Untuk Pejabat Esselon IIb',
        ],
        [
            'value' => 'D',
            'label' => 'Tingkat D - Untuk Pejabat Esselon III',
        ],
        [
            'value' => 'E',
            'label' => 'Tingkat E - Untuk Pejabat Esselon IV atau Golongan IV',
        ],
        [
            'value' => 'F',
            'label' => 'Tingkat F - Untuk Pejabat Golongan III dan II',
        ],
        [
            'value' => 'G',
            'label' => 'Tingkat G - Untuk Pejabat Golongan I',
        ],
    ];
    public $klasifikasiOptions = [];
    public $provincesOptions = [];
    public $regenciesOptions = [];
    public $districtsOptions = [];

    // Search properties
    public $searchOfficer = '';
    public $searchEmployee = '';

    public $dataSuratPerintah = [
        'id' => null,
        'klasifikasi_surat_id' => null,
        'prefix_nomor_surat' => null,
        'nomor_surat' => null,
        'instance_id' => null,
        'employee_giver_id' => null,
        'dasar' => null,
        'tujuan' => null,

        'province_id' => null,
        'regency_id' => null,

        'publication_date' => null,
        'publication_place' => null,
        'publication_employee_id' => null,
        'status' => 'draft',

        'alat_angkutan' => null,
        'tempat_berangkat' => 'Indralaya, Kabupaten Ogan Ilir',
        'tempat_tujuan' => null,
        'lama_perjalanan' => 0,
        'tanggal_berangkat' => null,
        'tanggal_pulang' => null,

        'publication_place' => 'Indralaya',
        'publication_date' => null,
    ];

    // loading
    public $isConverting = false;

    function mount($id = null)
    {
        $this->instances = Instance::when(auth()->user()->instance_id, function ($query) {
            $query->where('id', auth()->user()->instance_id);
        })
            ->get()->toArray();
        // add new instaces with id 0 for BUPATI OGAN ILIR
        $this->instances[] = [
            'id' => 0,
            'name' => 'BUPATI OGAN ILIR',
            'id_eoffice' => '00000',
        ];

        $this->availableYears = [];
        for ($year = Carbon::now()->year; $year >= 2024; $year--) {
            $this->availableYears[] = $year;
        }
        $this->availableMonths = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $this->selectedMonth = Carbon::now()->format('m');
        $this->selectedYear = Carbon::now()->format('Y');

        if ($id) {
            $this->isEdit = true;
            $this->dataId = $id;
            $this->loadSuratPerintah($id);
        } else {
            $this->dataSuratPerintah['publication_date'] = Carbon::now()->format('Y-m-d');
        }

        $this->loadProvincesOption();
        $this->loadKlasifikasiOption();
    }

    public function loadSuratPerintah($id)
    {
        $data = SuratPerintah::with(['employeeGiver', 'instance'])->findOrFail($id);
        if ($data->status == 'draft') {
            $this->isViewOnly = false;
        } else {
            $this->isViewOnly = true;
        }

        if ($this->isEdit == true && auth()->user()->id != $data->created_by) {
            $this->isViewOnly = true;
        }

        // check if $data->alat_angkutan has no value at $this->alatAngkutOptions array, then push it to the array
        if ($data->alat_angkutan && !in_array($data->alat_angkutan, array_column($this->alatAngkutOptions, 'value'))) {
            $this->alatAngkutOptions[] = $data->alat_angkutan;
        }

        if ($data->employeeGiver()) {
            $employeeGiver = $data->employeeGiver()->first();
            $this->selectedOfficer = [
                'id' => $employeeGiver->id,
                'nama_lengkap' => $employeeGiver->nama_lengkap,
                'nip' => $employeeGiver->nip,
                'jabatan' => $employeeGiver->jabatan,
                'eselon' => '',
                'golongan' => '',
            ];
            $publicationEmployee = $data->publicationEmployee()->first();
            $this->selectedSigner = [
                'id' => $publicationEmployee->id,
                'nama_lengkap' => $publicationEmployee->nama_lengkap,
                'nip' => $publicationEmployee->nip,
                'jabatan' => $publicationEmployee->jabatan,
                'eselon' => '',
                'golongan' => '',
            ];

            $this->commanding_officer = $employeeGiver->nama_lengkap;
            $this->selectedInstanceGiver = $employeeGiver->instance_id ?? 0;
            $this->isDisabledInstancesGiver = true;
        }

        $PrefixNomorSurat = KlasifikasiNomorSurat::find($data->klasifikasi_surat_id)->kode ?? '';
        $PrefixNomorSurat .= '/';
        // $NoPrefixNomorSurat = cut first 9 characters from $data->nomor_surat
        $NoPrefixNomorSurat = substr($data->nomor_surat, 11);
        // dd($NoPrefixNomorSurat);
        $this->dataSuratPerintah = [
            'id' => $data->id,
            'uuid' => $data->uuid,
            'klasifikasi_surat_id' => $data->klasifikasi_surat_id,
            'prefix_nomor_surat' => $PrefixNomorSurat,
            'nomor_surat' => $NoPrefixNomorSurat,
            'instance_id' => $data->instance_id,
            'employee_giver_id' => $data->employee_giver_id,
            'dasar' => $data->dasar,
            'tujuan' => $data->tujuan,
            'province_id' => $data->province_id,
            'regency_id' => $data->regency_id,

            'publication_date' => $data->publication_date ? Carbon::parse($data->publication_date)->format('Y-m-d') : null,
            'publication_place' => $data->publication_place,
            'publication_employee_id' => $data->publication_employee_id,
            'status' => $data->status,

            'alat_angkutan' => $data->alat_angkutan,
            'tempat_berangkat' => $data->tempat_berangkat,
            'tempat_tujuan' => $data->tempat_tujuan,
            'lama_perjalanan' => $data->lama_perjalanan,
            'tanggal_berangkat' => $data->tanggal_berangkat ? Carbon::parse($data->tanggal_berangkat)->format('Y-m-d') : null,
            'tanggal_pulang' => $data->tanggal_pulang ? Carbon::parse($data->tanggal_pulang)->format('Y-m-d') : null,

        ];

        if ($this->dataSuratPerintah['province_id']) {
            $this->loadRegenciesOptions($this->dataSuratPerintah['province_id']);
        }
        // dd($this->dataSuratPerintah);
    }

    /**
     * Convert HTML ordered/unordered lists to Word-compatible format
     */
    private function convertHtmlListToText($html)
    {
        if (empty($html)) {
            return '';
        }

        // Remove HTML tags except list items - handle Quill editor format
        $html = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $html);
        $html = preg_replace('/<span[^>]*contenteditable=["\']false["\'][^>]*><\/span>/i', '', $html);

        // Handle ordered lists (ol with li elements containing data-list="ordered")
        if (preg_match('/<ol[^>]*>(.*?)<\/ol>/is', $html, $olMatch)) {
            $listContent = $olMatch[1];
            if (preg_match_all('/<li[^>]*(?:data-list=["\']ordered["\'])?[^>]*>(.*?)<\/li>/is', $listContent, $matches)) {
                $result = '';
                foreach ($matches[1] as $index => $item) {
                    // Clean up the item content
                    $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                    $cleanItem = strip_tags($cleanItem);
                    $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                    $cleanItem = trim($cleanItem);

                    if (!empty($cleanItem)) {
                        $result .= ($index + 1) . '. ' . $cleanItem . "\n";
                    }
                }
                return trim($result);
            }
        }

        // Handle unordered lists (ul with li elements containing data-list="bullet")
        if (preg_match('/<ul[^>]*>(.*?)<\/ul>/is', $html, $ulMatch)) {
            $listContent = $ulMatch[1];
            if (preg_match_all('/<li[^>]*(?:data-list=["\']bullet["\'])?[^>]*>(.*?)<\/li>/is', $listContent, $matches)) {
                $result = '';
                foreach ($matches[1] as $item) {
                    // Clean up the item content
                    $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                    $cleanItem = strip_tags($cleanItem);
                    $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                    $cleanItem = trim($cleanItem);

                    if (!empty($cleanItem)) {
                        $result .= '• ' . $cleanItem . "\n";
                    }
                }
                return trim($result);
            }
        }

        // Handle standalone li elements with data-list="ordered" (Quill format without ol wrapper)
        if (preg_match_all('/<li[^>]*data-list=["\']ordered["\'][^>]*>(.*?)<\/li>/is', $html, $matches)) {
            $result = '';
            foreach ($matches[1] as $index => $item) {
                // Clean up the item content
                $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                $cleanItem = strip_tags($cleanItem);
                $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                $cleanItem = trim($cleanItem);

                if (!empty($cleanItem)) {
                    $result .= ($index + 1) . '. ' . $cleanItem . "\n";
                }
            }
            return trim($result);
        }

        // Handle standalone li elements with data-list="bullet" (Quill format without ul wrapper)
        if (preg_match_all('/<li[^>]*data-list=["\']bullet["\'][^>]*>(.*?)<\/li>/is', $html, $matches)) {
            $result = '';
            foreach ($matches[1] as $item) {
                // Clean up the item content
                $cleanItem = preg_replace('/<span[^>]*class=["\']ql-ui["\'][^>]*>.*?<\/span>/i', '', $item);
                $cleanItem = strip_tags($cleanItem);
                $cleanItem = html_entity_decode($cleanItem, ENT_QUOTES, 'UTF-8');
                $cleanItem = trim($cleanItem);

                if (!empty($cleanItem)) {
                    $result .= '• ' . $cleanItem . "\n";
                }
            }
            return trim($result);
        }

        // If no list found, just clean the HTML
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text); // Normalize whitespace
        return trim($text);
    }

    public function fetchSemestaOfficers()
    {
        $this->rawSemestaOfficers = [];  // Store unfiltered officers
        $this->searchOfficer = '';        // Search query for officers
        $this->semestaOfficers = [];
        $this->showOfficers = false;

        if (!$this->selectedInstanceGiver) {
            LivewireAlert::title('Peringatan')
                ->text('Silakan pilih instansi terlebih dahulu')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        $instance = Instance::find($this->selectedInstanceGiver);

        if (!$instance || !$instance->id_eoffice) {
            LivewireAlert::title('Peringatan')
                ->text('Instansi tidak memiliki kode eOffice')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        $uri = 'https://semesta.oganilirkab.go.id/api/daftar-pegawai';

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'PostmanRuntime/7.44.1',
                'x-api-key' => '!@#Op3nAp1K3584n9p0l',
            ])->timeout(30)->post($uri, [
                'id_skpd' => $instance->id_eoffice,
            ]);

            if ($response->status() == 200) {
                $this->semestaOfficers = $response->json()['data'] ?? [];
                $this->rawSemestaOfficers = $this->semestaOfficers;

                if ($this->selectedInstanceGiver == 15) {
                    // $this->semestaOfficers = collect($this->semestaOfficers)->where('jabatan', 'SEKRETARIS DAERAH KAB OGAN ILIR');
                    $this->semestaOfficers = collect($this->semestaOfficers)->where('nama_lengkap', 'DICKY SYAILENDRA');
                    // dd($this->semestaOfficers);
                } elseif ($this->selectedInstanceGiver == 7) {
                    $this->semestaOfficers = collect($this->semestaOfficers);
                } else {
                    $this->semestaOfficers = collect($this->semestaOfficers)->where('kepala_skpd', 'Y');
                }
                if (count($this->semestaOfficers) == 1) {
                    // auto select if only one officer
                    $officerData = collect($this->semestaOfficers)->first();
                    $this->selectOfficer($officerData);
                    return;
                }

                LivewireAlert::title('Berhasil')
                    ->text('Data pejabat berhasil dimuat')
                    ->position('top-end')
                    ->timer(3000)
                    ->success()
                    ->toast()
                    ->show();

                $this->showOfficers = true;
            } else {
                LivewireAlert::title('Gagal')
                    ->text('Gagal mengambil data pejabat dari server')
                    ->position('top-end')
                    ->timer(3000)
                    ->warning()
                    ->toast()
                    ->show();
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            LivewireAlert::title('Error')
                ->text('Error: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->error()
                ->toast()
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('Terjadi kesalahan: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->error()
                ->toast()
                ->show();
        }
    }

    public function updatedSearchOfficer($value)
    {
        if (empty($value)) {
            $this->semestaOfficers = $this->rawSemestaOfficers;
            return;
        }

        $search = strtolower($value);
        $this->semestaOfficers = array_filter($this->rawSemestaOfficers, function ($officer) use ($search) {
            $nama = strtolower($officer['nama_lengkap'] ?? '');
            $nip = strtolower($officer['nip'] ?? '');
            $jabatan = strtolower($officer['jabatan'] ?? '');

            return stripos($nama, $search) !== false ||
                stripos($nip, $search) !== false ||
                stripos($jabatan, $search) !== false;
        });
    }

    public function selectOfficer($officerData)
    {
        $this->selectedOfficer = $officerData;
        $this->commanding_officer = $officerData['nama_lengkap'] ?? '';
        $this->dataSuratPerintah['instance_id'] = $this->selectedInstanceGiver;
        // $this->dataSuratPerintah['employee_giver_id'] = $officerData['semesta_id'] ?? null;

        // auto select for publication employee if not selected
        if (!$this->selectedSigner) {
            $this->selectedSigner = $officerData;
        }

        // Dispatch event to refresh Select2
        $this->dispatch('select2:refresh');
        $this->showOfficers = false;
    }

    public function fetchSemestaUsers()
    {
        $this->rawSemestaUsers = [];  // Store unfiltered users
        $this->searchEmployee = '';       // Search query for employees
        $this->semestaUsers = [];
        $this->showEmployees = false;

        if (!$this->selectedInstance) {
            LivewireAlert::title('Peringatan')
                ->text('Silakan pilih instansi terlebih dahulu')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        $instance = Instance::find($this->selectedInstance);

        if (!$instance || !$instance->id_eoffice) {
            LivewireAlert::title('Peringatan')
                ->text('Instansi tidak memiliki kode eOffice')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        $uri = 'https://semesta.oganilirkab.go.id/api/daftar-pegawai';

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'PostmanRuntime/7.44.1',
                'x-api-key' => '!@#Op3nAp1K3584n9p0l',
            ])->timeout(30)->post($uri, [
                'id_skpd' => $instance->id_eoffice,
            ]);

            if ($response->status() == 200) {
                $this->semestaUsers = $response->json()['data'] ?? [];
                $this->rawSemestaUsers = $this->semestaUsers;

                LivewireAlert::title('Berhasil')
                    ->text('Data pegawai berhasil dimuat')
                    ->position('top-end')
                    ->timer(3000)
                    ->success()
                    ->toast()
                    ->show();
                $this->showEmployees = true;
            } else {
                LivewireAlert::title('Gagal')
                    ->text('Gagal mengambil data pegawai dari server')
                    ->position('top-end')
                    ->timer(3000)
                    ->warning()
                    ->toast()
                    ->show();
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            LivewireAlert::title('Error')
                ->text('Error: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->error()
                ->toast()
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('Terjadi kesalahan: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->error()
                ->toast()
                ->show();
        }
    }

    public function updatedSearchEmployee($value)
    {
        if (empty($value)) {
            $this->semestaUsers = $this->rawSemestaUsers;
            return;
        }

        $search = strtolower($value);
        $this->semestaUsers = array_filter($this->rawSemestaUsers, function ($user) use ($search) {
            $nama = strtolower($user['nama_lengkap'] ?? '');
            $nip = strtolower($user['nip'] ?? '');
            $jabatan = strtolower($user['jabatan'] ?? '');

            return stripos($nama, $search) !== false ||
                stripos($nip, $search) !== false ||
                stripos($jabatan, $search) !== false;
        });
    }

    public function toggleShowOfficers()
    {
        $this->showOfficers = ! $this->showOfficers;
    }

    public function toggleShowEmployees()
    {
        $this->showEmployees = ! $this->showEmployees;
    }

    public function updated($field)
    {
        if ($field == 'selectedInstanceGiver') {
            if ($this->selectedInstanceGiver == 0) {
                $this->selectedOfficer = [
                    'nama_lengkap' => 'Panca Wijaya Akbar, S.H.',
                    'nip' => 'N/A',
                    'jabatan' => 'BUPATI OGAN ILIR',
                    'eselon' => '',
                    'golongan' => '',
                ];
                $this->selectedSigner = $this->selectedOfficer;
                $this->commanding_officer = 'bupati';
                $this->isDisabledInstances = false;
                $this->showOfficers = false;
                $this->semestaOfficers = [];
            } else if ($this->selectedInstanceGiver != 0) {
                // $this->selectedInstance = $this->selectedInstanceGiver;
                $this->isDisabledInstancesGiver = true;
                $this->selectedOfficer = null;
                $this->selectedSigner = null;
                $this->commanding_officer = null;
            }
        }

        if ($field == 'dataSuratPerintah.lama_perjalanan' || $field == 'dataSuratPerintah.tanggal_berangkat') {
            if ($this->dataSuratPerintah['lama_perjalanan'] && is_numeric($this->dataSuratPerintah['lama_perjalanan'])) {
                $startDate = Carbon::parse($this->dataSuratPerintah['tanggal_berangkat']);
                $endDate = $startDate->copy()->addDays((int)$this->dataSuratPerintah['lama_perjalanan']);
                $this->dataSuratPerintah['tanggal_pulang'] = $endDate->format('Y-m-d');
            }
        }

        if ($field == 'dataSuratPerintah.klasifikasi_surat_id') {
            // Handle changes related to klasifikasi_surat_id if needed
            $prefixNomorSurat = KlasifikasiNomorSurat::find($this->dataSuratPerintah['klasifikasi_surat_id'])->kode ?? '';
            $this->dataSuratPerintah['prefix_nomor_surat'] = $prefixNomorSurat . '/';
            // $this->dataSuratPerintah['nomor_surat'] = $prefixNomorSurat;
        }

        if ($field == 'dataSuratPerintah.province_id') {
            $this->dataSuratPerintah['regency_id'] = null;
            $this->loadRegenciesOptions($this->dataSuratPerintah['province_id']);
        }

        // $this->dispatch('select2:refresh');
    }

    public function submitForm($type = null)
    {
        if ($this->isViewOnly) {
            LivewireAlert::title('Peringatan')
                ->text('Surat Perintah sudah ditandatangani dan tidak dapat diubah.')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }
        $this->validate([
            'commanding_officer' => 'required',

            'dataSuratPerintah.prefix_nomor_surat' => 'required|string|max:255',
            'dataSuratPerintah.nomor_surat' => 'required|string|max:255',
            'dataSuratPerintah.instance_id' => 'nullable|integer',
            'dataSuratPerintah.dasar' => 'required|string',
            'dataSuratPerintah.tujuan' => 'required|string',

            'dataSuratPerintah.alat_angkutan' => 'required',
            'dataSuratPerintah.tempat_tujuan' => 'required|string',
            'dataSuratPerintah.tanggal_berangkat' => 'required|date',
            'dataSuratPerintah.tanggal_pulang' => 'required|date|after_or_equal:dataSuratPerintah.tanggal_berangkat',
            'dataSuratPerintah.lama_perjalanan' => 'required|integer|min:0|max:10',

            'dataSuratPerintah.publication_place' => 'required|string|max:255',
            'dataSuratPerintah.publication_date' => 'required|date|before:dataSuratPerintah.tanggal_berangkat',
        ], [
            'dataSuratPerintah.lama_perjalanan.max' => 'Lama perjalanan maksimal 10 hari.',
            'dataSuratPerintah.lama_perjalanan.min' => 'Lama perjalanan minimal 1 hari.',
        ], [
            'commanding_officer' => 'Pejabat Perintah',

            'dataSuratPerintah.nomor_surat' => 'Nomor Surat',
            'dataSuratPerintah.instance_id' => 'Instansi',
            'dataSuratPerintah.dasar' => 'Dasar Perjalanan',
            'dataSuratPerintah.tujuan' => 'Maksud Perjalanan Dinas',
            'dataSuratPerintah.alat_angkutan' => 'Alat Angkutan',
            'dataSuratPerintah.tempat_tujuan' => 'Tempat Tujuan',
            'dataSuratPerintah.tanggal_berangkat' => 'Tanggal Berangkat',
            'dataSuratPerintah.tanggal_pulang' => 'Tanggal Pulang',
            'dataSuratPerintah.lama_perjalanan' => 'Lama Perjalanan',
            'dataSuratPerintah.publication_place' => 'Tempat Dikeluarkan SPT',
            'dataSuratPerintah.publication_date' => 'Tanggal Dikeluarkan SPT',
        ]);

        // update/create employee data
        if ($this->selectedOfficer && $this->selectedOfficer['nip']) {
            if ($this->commanding_officer == 'bupati') {
                // FOR BUPATI OGAN ILIR SPECIAL CASE
                $employeeOfficer = Employee::where('nip', '1000')->first();
                if (!$employeeOfficer) {
                    $employeeOfficer = Employee::create([
                        'semesta_id' => '4842',
                        'nama_lengkap' => $this->selectedOfficer['nama_lengkap'] ?? '',
                        'nip' => '1000',
                        'jenis_pegawai' => 'bupati',
                        'instance_id' => null,
                        'id_skpd' => null,
                        'id_jabatan' => null,
                        'jabatan' => 'BUPATI OGAN ILIR',
                        'kepala_skpd' => null,
                        'foto_pegawai' => null,
                        'email' => null,
                        'no_hp' => null,
                        'golongan' => null,
                        'pangkat' => null,
                    ]);
                }
            } else {
                // FOR ORIGINAL OFFICER DATA
                $employeeOfficer = Employee::where('nip', $this->selectedOfficer['nip'])->first();
                if (!$employeeOfficer) {
                    $employeeOfficer = Employee::create([
                        'semesta_id' => $this->selectedOfficer['id'],
                        'nama_lengkap' => $this->selectedOfficer['nama_lengkap'] ?? '',
                        'nip' => $this->selectedOfficer['nip'] ?? '',
                        'jenis_pegawai' => $this->selectedOfficer['jenis_pegawai'] ?? '',
                        'instance_id' => $this->selectedInstanceGiver,
                        'id_skpd' => $this->selectedOfficer['id_skpd'] ?? null,
                        'id_jabatan' => $this->selectedOfficer['id_jabatan'] ?? null,
                        'jabatan' => $this->selectedOfficer['jabatan'] ?? '',
                        'kepala_skpd' => $this->selectedOfficer['kepala_skpd'] ?? 'N',
                        'foto_pegawai' => $this->selectedOfficer['foto_pegawai'] ?? null,
                        'email' => $this->selectedOfficer['email'] ?? null,
                        'no_hp' => $this->selectedOfficer['no_hp'] ?? null,
                        'eselon' => $this->selectedOfficer['eselon'] ?? null,
                        'golongan' => $this->selectedOfficer['golongan'] ?? null,
                        'pangkat' => $this->selectedOfficer['pangkat'] ?? null,
                    ]);
                } else {
                    // Update instance if changed
                    if ($employeeOfficer->instance_id != $this->selectedInstanceGiver) {
                        $employeeOfficer->instance_id = $this->selectedInstanceGiver;
                        $employeeOfficer->save();
                    }
                }
            }
        }

        if (!$employeeOfficer) {
            LivewireAlert::title('Peringatan')
                ->text('Sedang diproses. Harap simpan ulang setelah beberapa saat!')
                ->position('top-end')
                ->timer(5000)
                ->warning()
                ->toast()
                ->show();
            return;
        }

        DB::beginTransaction();
        try {
            $data = $this->dataSuratPerintah;
            $data['employee_giver_id'] = $employeeOfficer->id ?? null;
            $data['publication_employee_id'] = $employeeOfficer->id ?? null;
            $data['nomor_surat'] = $this->dataSuratPerintah['prefix_nomor_surat'] . $this->dataSuratPerintah['nomor_surat'];
            if ($this->isEdit) {
                // update existing surat perintah
                $suratPerintah = SuratPerintah::find($this->dataId);
                $suratPerintah->update($data);

                $this->updateSppds();
            } else {
                // create new surat perintah
                $data['employee_giver_instance_id'] = $employeeOfficer->instance_id ?? null;
                $data['instance_id'] = auth()->user()->instance_id ?? $this->dataSuratPerintah['instance_id'];
                $suratPerintah = SuratPerintah::create($data);
            }

            DB::commit();
            if ($type == 'with_sppd') {
                return redirect()->route('admin.surat-perintah.sppd', ['id' => $suratPerintah->id]);
            } else if ($type == 'edit') {
                LivewireAlert::title('Berhasil')
                    ->text('Surat Perintah berhasil diperbarui')
                    ->position('top-end')
                    ->timer(3000)
                    ->success()
                    ->toast()
                    ->show();
                return;
            } else {
                return redirect()->route('admin.surat-perintah.index');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('Gagal menyimpan Surat Perintah: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->toast()
                ->error()
                ->show();
        }
        DB::commit();
    }

    private function updateSppds()
    {
        $sppds = SPPD::where('surat_perintah_id', $this->dataId)->get();;
        foreach ($sppds as $sppd) {
            $sppd->update([
                'maksud_perjalanan' => $this->dataSuratPerintah['tujuan'],
                'alat_angkutan' => $this->dataSuratPerintah['alat_angkutan'],
                'tempat_berangkat' => $this->dataSuratPerintah['tempat_berangkat'],
                'tempat_tujuan' => $this->dataSuratPerintah['tempat_tujuan'],
                'tanggal_berangkat' => $this->dataSuratPerintah['tanggal_berangkat'],
                'tanggal_pulang' => $this->dataSuratPerintah['tanggal_pulang'],

                'publication_place' => $this->dataSuratPerintah['publication_place'],
                'publication_date' => $this->dataSuratPerintah['publication_date'],
                'publication_employee_id' => $this->dataSuratPerintah['publication_employee_id'],

                'province_id' => $this->dataSuratPerintah['province_id'],
                'regency_id' => $this->dataSuratPerintah['regency_id'],
            ]);
        }
    }

    public function loadKlasifikasiOption()
    {
        $datas = KlasifikasiNomorSurat::orderBy('id', 'asc')
            ->with('children')
            ->whereIn('id', ['822558', '822562'])
            ->get();
        $this->klasifikasiOptions = $datas->toArray();
    }

    public function loadProvincesOption()
    {
        $datas = DB::table('reg_provinces')->get();
        $this->provincesOptions = collect($datas)
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })->toArray();
        // dd($this->provincesOptions);
    }

    public function loadRegenciesOptions($provinceId)
    {
        $datas = DB::table('reg_regencies')
            ->where('province_id', $provinceId)
            ->get();
        $this->regenciesOptions = collect($datas)
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })->toArray();
        $this->dispatch('select2:refresh');
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

        $penandaTangan = $this->dataSuratPerintah['employee_giver_id'] ? Employee::find($this->dataSuratPerintah['employee_giver_id']) : null;

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
            $fileWord = $this->generateSuratPerintahTugas($templatePath, $penandaTangan, $this->dataSuratPerintah, $penandaTangan->instance);
        } else if ($kopType == 'bupati') {
            $fileWord = $this->generateSuratPerintahTugas($templatePath, $penandaTangan, $this->dataSuratPerintah);
        } else if ($kopType == 'sekda') {
            $fileWord = $this->generateSuratPerintahTugas($templatePath, $penandaTangan, $this->dataSuratPerintah);
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
        $command = "/Applications/LibreOffice.app/Contents/MacOS/soffice --headless --convert-to pdf {$path} --outdir {$savePath} 2>&1";

        // Linux LibreOffice shell command to convert doc to pdf
        // $command = "libreoffice --headless --convert-to pdf {$path} --outdir {$savePath} 2>&1";

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
        $dasar = $this->convertHtmlListToText($previewData['dasar'] ?? '');
        $tujuan = $this->convertHtmlListToText($previewData['tujuan'] ?? '');
        $templateProcessor->setValue('dasar', $dasar);
        $templateProcessor->setValue('untuk', $tujuan);
        $templateProcessor->setValue('tanggal_surat', $previewData['publication_date'] ? Carbon::parse($previewData['publication_date'])->isoFormat('D MMMM Y') : '-');

        $templateProcessor->setValue('nama_penandatangan', $penandaTangan['nama_lengkap'] ?? '-');
        $templateProcessor->setValue('nip_penandatangan', '');
        $templateProcessor->setValue('jabatan_penandatangan', $penandaTangan['jabatan'] ?? '-');

        // QR Code
        $qrCodeUrl = $this->generateQrCodeSPT('spt', 'bupati', $previewData);
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

        $maksudPerjalananDinas = $this->convertHtmlListToText($DataSPPD['maksud_perjalanan'] ?? '');
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
        // $dasar = $this->convertHtmlListToText($DataSPPD['dasar'] ?? '');

        // $templateProcessor->setValue('dasar', $dasar);

        // $templateProcessor->setValue('tanggal_surat', $DataSPPD['publication_date'] ? Carbon::parse($DataSPPD['publication_date'])->isoFormat('D MMMM Y') : '-');
        $templateProcessor->setValue('tanggal_surat', $DataSPPD['publication_date'] ?? '-');

        $templateProcessor->setValue('nama_penandatangan', $penandaTangan->nama_lengkap ?? '-');
        $templateProcessor->setValue('nip_penandatangan', $penandaTangan->nip ?? '-');
        $templateProcessor->setValue('jabatan_penandatangan', $penandaTangan->jabatan ?? '-');

        // QR Code
        $qrCodeUrl = $this->generateQrCodeSPPD('sppd', 'perangkat_daerah', $DataSPPD);
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

            $qrCodeUrl = asset('storage/' . $filePath);

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

            $qrCodeUrl = asset('storage/' . $filePath);

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
        return view('livewire.admin.surat-perintah.form');
    }
}
