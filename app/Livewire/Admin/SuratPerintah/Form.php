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

    public function render()
    {
        return view('livewire.admin.surat-perintah.form');
    }
}
