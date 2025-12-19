<?php

namespace App\Livewire\Admin;

use App\Models\Sppd;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Instance;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('components.layouts.app')]
#[Title('Form SPPD')]
class SppdForm extends Component
{
    public $sppdId;
    public $suratPerintahId;
    public $isEdit = false;
    public $currentTab = 'biaya';
    // public $currentTab = 'preview';
    public $isDisabledInstancesGiver = false;
    public $isDisabledInstances = false;

    // Form fields sesuai dengan 9 data points requirement
    public $sppd_number; // Nomor SPPD
    public $commanding_officer; // 1. Pejabat yang memberi perintah
    public $employee_id; // 2. Nama/NIP pegawai yang diperintahkan
    public $employee_rank; // 2a. Pangkat dan golongan
    public $employee_position; // 2b. Jabatan/Instansi
    public $purpose; // 3. Maksud perjalanan dinas
    public $transportation; // 4. Alat angkut yang dipergunakan
    public $starting_place = 'Indralaya'; // 5a. Tempat berangkat
    public $destination_places; // 5. Tempat tujuan
    public $departure_date; // 6a. Lamanya perjalanan dinas - tanggal berangkat
    public $return_date; // 6b. Lamanya perjalanan dinas - tanggal harus kembali
    public $departure_on; // 7a. Pada tanggal
    public $return_on; // 7b. Ke
    public $department_head; // 8a. Dikeluarkan di
    public $issued_date; // 8b. Tanggal
    public $cost_level; // 9. Instansi
    public $dataSuratPerintah = [];

    public $isLoadedKodeRekening = false;
    public $alatAngkutOptions = [
        'Pesawat Udara',
        'Kapal Laut',
        'Kereta Api',
        'Transportasi Umum Darat',
        'Kendaraan Dinas Operasional',
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

    public $availableYears;
    public $availableMonths;
    public $selectedYear;
    public $selectedMonth;

    // API data
    public $semestaUsers = [];
    public $rawSemestaUsers = [];
    public $selectedEmployee = null;
    public $semestaOfficers = []; // For commanding officers
    public $rawSemestaOfficers = []; // Raw data for search
    public $selectedOfficer = null;
    public $showOfficers = false;
    public $showEmployees = false;
    public $instances = [];
    public $selectedInstanceGiver = null;
    public $selectedInstance = null;
    public $arrSubKegiatan = [];
    public $subKegiatan;
    public $subKegiatanData;
    public $arrKodeRekening = [];
    public $kodeRekening;
    public $kodeRekeningData;

    // Search properties
    public $searchOfficer = '';
    public $searchEmployee = '';

    // Preview Data
    public $previewData = [];

    protected $rules = [
        'sppd_number' => 'required|string|max:255',
        'commanding_officer' => 'required',
        'employee_id' => 'nullable|exists:employees,id',
        'purpose' => 'required|string',
        'transportation' => 'required|string|max:255',
        'starting_place' => 'required|string|max:255',
        'destination_places' => 'required|string',
        'departure_date' => 'required|date',
        'return_date' => 'required|date|after_or_equal:departure_date',
        'cost_level' => 'required|in:A,B,C,D,E,F,G',
    ];

    protected $messages = [
        'sppd_number.required' => 'Nomor SPPD harus diisi',
        'commanding_officer.required' => 'Pejabat yang memberi perintah harus diisi',
        'employee_id.exists' => 'Pegawai yang dipilih tidak valid',
        'purpose.required' => 'Maksud perjalanan dinas harus diisi',
        'transportation.required' => 'Alat angkut yang dipergunakan harus diisi',
        'starting_place.required' => 'Tempat berangkat harus diisi',
        'destination_places.required' => 'Tempat tujuan harus diisi',
        'departure_date.required' => 'Tanggal berangkat harus diisi',
        'departure_date.date' => 'Tanggal berangkat tidak valid',
        'return_date.required' => 'Tanggal harus kembali harus diisi',
        'return_date.date' => 'Tanggal harus kembali tidak valid',
        'return_date.after_or_equal' => 'Tanggal harus kembali harus setelah atau sama dengan tanggal berangkat',
        'cost_level.required' => 'Tingkat biaya harus dipilih',
    ];

    public function mount($id = null)
    {
        // $this->instances = Instance::all()->toArray();
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
            $this->sppdId = $id;
            $this->loadSppd($id);
        } else {
            // Set default values
            $this->issued_date = now()->format('Y-m-d');
            $this->departure_date = now()->format('Y-m-d');
            $this->return_date = now()->addDays(1)->format('Y-m-d');

            // Check if duplicating from existing SPPD
            if (session()->has('duplicate_sppd_data')) {
                $data = session('duplicate_sppd_data');

                // Load data except nomor_sppd and employee selections
                $employeeGiver = Employee::find($data['employee_giver_id']);
                $this->selectedInstanceGiver = $employeeGiver->instance_id;
                $this->selectedOfficer = $employeeGiver;
                $this->showOfficers = true;

                $employeeExecutor = Employee::find($data['employee_executor_id']);
                $this->selectedInstance = $employeeExecutor->instance_id;
                $this->isDisabledInstances = true;
                $this->fetchSemestaUsers();
                $this->currentTab = 'pegawai';

                $this->commanding_officer = $data['employee_giver_id'];
                $this->purpose = $data['maksud_perjalanan'];
                $this->transportation = $data['alat_angkutan'];
                $this->starting_place = $data['tempat_berangkat'];
                $this->destination_places = $data['tempat_tujuan'];
                $this->departure_date = $data['tanggal_berangkat'];
                $this->return_date = $data['tanggal_pulang'];
                $this->cost_level = $data['tingkat_biaya'];
                $this->issued_date = $data['publication_date'];

                $this->subKegiatan = $data['kode_sub_kegiatan'];
                $this->subKegiatanData = [
                    'fullcode' => $data['kode_sub_kegiatan'],
                    'name' => $data['uraian_sub_kegiatan'],
                    'pagu_induk' => $data['anggaran_sub_kegiatan'],
                ];

                $this->kodeRekening = $data['kode_rekening'];
                $this->kodeRekeningData = [
                    'fullcode' => $data['kode_rekening'],
                    'name' => $data['uraian_rekening'],
                    'pagu_induk' => $data['anggaran_rekening'],
                ];

                // Reset selected employees (kosongkan)
                $this->selectedEmployee = null;

                // Clear session after loading
                session()->forget('duplicate_sppd_data');

                LivewireAlert::title('Info')
                    ->text('Data SPPD berhasil diduplikasi. Silakan isi nomor SPPD dan pilih pegawai.')
                    ->position('top-end')
                    ->timer(4000)
                    ->toast()
                    ->info()
                    ->show();
            } else {
                if (auth()->user()->instance_id) {
                    if ($this->isEdit == false) {
                        $this->selectedInstanceGiver = auth()->user()->instance_id;
                        $this->selectedInstance = auth()->user()->instance_id;
                        $this->fetchSemestaOfficers();
                        $this->fetchSemestaUsers();
                    }
                }
            }
        }
        if (auth()->user()->instance_id) {
            // $this->isDisabledInstancesGiver = true;
            $this->isDisabledInstances = true;
        }
    }

    public function goToPrevTab()
    {
        if ($this->currentTab == 'biaya') {
            $this->currentTab = 'detail';
        } elseif ($this->currentTab == 'detail') {
            $this->currentTab = 'pegawai';
        } elseif ($this->currentTab == 'pegawai') {
            $this->currentTab = 'pejabat';
        } elseif ($this->currentTab == 'biaya') {
            $this->currentTab = 'detail';
        }
    }

    public function goToNextTab()
    {
        if ($this->currentTab == 'pejabat') {
            if ($this->toastedValidation([
                'selectedOfficer',
            ]) === false) {
                return;
            }
            $this->currentTab = 'pegawai';
        } elseif ($this->currentTab == 'pegawai') {
            if ($this->toastedValidation([
                'selectedEmployee',
            ]) === false) {
                return;
            }
            $this->currentTab = 'detail';
        } elseif ($this->currentTab == 'detail') {
            if ($this->toastedValidation([
                'sppd_number',
                'commanding_officer',
                'purpose',
                'transportation',
                'starting_place',
                'destination_places',
                'departure_date',
                'return_date',
                // 'cost_level',
                // 'kodeRekening',
            ]) === false) {
                return;
            }
            $this->currentTab = 'biaya';
        }
    }

    public function loadSppd($id)
    {
        $sppd = SPPD::with(['employeeGiver', 'employeeExecutor', 'instance'])
            ->findOrFail($id);
        $this->suratPerintahId = $sppd->surat_perintah_id;

        $this->dataSuratPerintah = $sppd->suratPerintah;

        if ($sppd->employeeGiver()) {
            $employeeGiver = $sppd->employeeGiver()->first();
            $this->selectedInstanceGiver = $employeeGiver->instance_id ?? 0;
            $this->selectedOfficer = $employeeGiver;
            $this->showOfficers = true;
        }

        if ($sppd->employeeExecutor()) {
            $employeeExecutor = $sppd->employeeExecutor()->first();
            $this->selectedInstance = $employeeExecutor->instance_id;
            $this->selectedEmployee = $employeeExecutor;
            $this->showEmployees = true;
        }

        $this->sppdId = $sppd->id;
        $this->sppd_number = $sppd->nomor_sppd;
        $this->commanding_officer = $sppd->employee_giver_id;
        $this->employee_id = $sppd->employee_executor_id;
        $this->purpose = $sppd->maksud_perjalanan;
        $this->transportation = $sppd->alat_angkutan;
        $this->starting_place = $sppd->tempat_berangkat;
        $this->destination_places = $sppd->tempat_tujuan;
        $this->departure_date = Carbon::parse($sppd->tanggal_berangkat)->format('Y-m-d');
        $this->return_date = Carbon::parse($sppd->tanggal_kembali)->format('Y-m-d');
        $this->cost_level = $sppd->tingkat_biaya;
        $this->issued_date = Carbon::parse($sppd->publication_date)->format('Y-m-d');
        $this->department_head = $sppd->publication_place;

        $this->subKegiatan = $sppd->kode_sub_kegiatan;
        if ($sppd->kode_sub_kegiatan) {
            // $this->fetchKodeRekening();
        }
        $this->subKegiatanData = [
            'fullcode' => $sppd->kode_sub_kegiatan,
            'name' => $sppd->uraian_sub_kegiatan,
            'pagu_induk' => $sppd->anggaran_sub_kegiatan,
        ];

        // if ($sppd->kode_rekening) {
        //     $this->fetchKodeRekening();
        // }
        $this->kodeRekening = $sppd->kode_rekening;
        $this->kodeRekeningData = [
            'fullcode' => $sppd->kode_rekening,
            'name' => $sppd->uraian_rekening,
            'pagu_induk' => $sppd->anggaran_rekening,
        ];

        if ($sppd->employee) {
            $this->employee_rank = $sppd->employee->rank;
            $this->employee_position = $sppd->employee->position;
        }

        $this->previewData = [
            'nomor_sppd' => $sppd->nomor_sppd,

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
            'tempat_tujuan' => $sppd->tempat_tujuan,

            'lama_perjalanan' => $sppd->lama_perjalanan,
            'tanggal_berangkat' => Carbon::parse($sppd->tanggal_berangkat)->isoFormat('D MMMM Y'),
            'tanggal_kembali' => Carbon::parse($sppd->tanggal_kembali)->isoFormat('D MMMM Y'),

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
            'issued_name' => $sppd->employeeGiver ? $sppd->employeeGiver->nama_lengkap : '',
            'issued_nip' => $sppd->employeeGiver ? $sppd->employeeGiver->nip : '',
            'issued_pangkat' => $sppd->employeeGiver ? $sppd->employeeGiver->pangkat : '',
            'issued_golongan' => $sppd->employeeGiver ? $sppd->employeeGiver->golongan : '',
            'issued_jabatan' => $sppd->employeeGiver ? $sppd->employeeGiver->jabatan : '',
            'issued_jabatan_title' => $sppd->employeeGiver ? (str_contains(strtolower($sppd->employeeGiver->jabatan), 'kepala dinas') ? 'KEPALA DINAS' : '') : '',
            'issued_instance_name' => $sppd->employeeGiver && $sppd->employeeGiver->instance ? $sppd->employeeGiver->instance->name : '',
        ];
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
                $this->commanding_officer = 'bupati';
                $this->isDisabledInstances = false;
                $this->showOfficers = false;
                $this->semestaOfficers = [];
            } else if ($this->selectedInstanceGiver != 0) {
                $this->selectedInstance = $this->selectedInstanceGiver;
                $this->isDisabledInstancesGiver = true;
                $this->selectedOfficer = null;
                $this->commanding_officer = null;
            }
        }

        if ($field == 'subKegiatan') {
            $arrSubKegiatan = $this->arrSubKegiatan;
            if ($arrSubKegiatan) {
                foreach ($arrSubKegiatan as $item) {
                    if ($item['fullcode'] == $this->subKegiatan) {
                        $this->subKegiatanData = $item;
                        break;
                    }
                }
            }
            // dd($this->subKegiatanData);
            $this->arrKodeRekening = collect($this->subKegiatanData['kode_rekening'] ?? []);
            $this->kodeRekening = null;
            $this->kodeRekeningData = null;
        }

        if ($field == 'kodeRekening') {
            if ($this->arrKodeRekening) {
                foreach ($this->arrKodeRekening as $item) {
                    if ($item['fullcode'] == $this->kodeRekening) {
                        $this->kodeRekeningData = $item;
                        break;
                    }
                }
            }
        }
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

    public function toggleShowOfficers()
    {
        $this->showOfficers = ! $this->showOfficers;
    }

    public function toggleShowEmployees()
    {
        $this->showEmployees = ! $this->showEmployees;
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

    public function fetchKodeRekening()
    {
        $this->isLoadedKodeRekening = false;
        if ($this->selectedInstance === null) {
            LivewireAlert::title('Peringatan')
                ->text('Silakan pilih instansi terlebih dahulu')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }
        // $year = Carbon::now()->year;
        $year = $this->selectedYear;
        $month = $this->selectedMonth;

        $this->kodeRekening = null;
        $this->kodeRekeningData = null;
        $this->subKegiatan = null;
        $this->subKegiatanData = null;

        $uriKodeRekening = 'https://sicaramapis.oganilirkab.go.id/api/local/sppd/getRekeningPerjadin';
        // $uriKodeRekening = 'http://127.0.0.1:8000/api/local/sppd/getRekeningPerjadin';
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'PostmanRuntime/7.44.1',
            ])->timeout(30)->get($uriKodeRekening, [
                'year' => $year,
                'month' => $month,
                'instance_id' => $this->selectedInstance,
            ]);
            if ($response->status() == 200) {
                $responseData = $response->json()['data'] ?? [];
                $responseData = collect($responseData)->values()->all();
                $this->arrSubKegiatan = $responseData ?? [];
                $this->isLoadedKodeRekening = true;
                return;
            } else {
                $this->arrKodeRekening = [];
                LivewireAlert::title('Gagal')
                    ->text('Gagal mengambil data dari server')
                    ->position('top-end')
                    ->timer(3000)
                    ->warning()
                    ->toast()
                    ->show();
                return;
            }
        } catch (\Exception $e) {
            $this->arrKodeRekening = [];
            LivewireAlert::title('Error')
                ->text('Terjadi kesalahan saat mengambil data dari server: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->error()
                ->toast()
                ->show();
            return;
        }
    }

    public function selectEmployee($employeeData)
    {
        $this->selectedEmployee = $employeeData;
        $this->employee_rank = $employeeData['pangkat'] ?? '';
        $this->employee_position = $employeeData['jabatan'] ?? '';

        // Auto-detect cost level based on eselon and golongan
        $detectedLevel = $this->detectCostLevel($employeeData);
        $this->cost_level = $detectedLevel;

        // Get level name for notification
        $levelName = collect($this->tingkatOptions)->firstWhere('value', $detectedLevel)['label'] ?? "Tingkat $detectedLevel";

        // Show notification about auto-selected cost level
        LivewireAlert::title('Tingkat Biaya Terdeteksi')
            ->text("Tingkat biaya otomatis diset ke: $levelName")
            ->position('top-end')
            ->timer(4000)
            ->info()
            ->toast()
            ->show();

        // Dispatch event to refresh Select2
        $this->dispatch('select2:refresh');
        $this->showEmployees = false;
    }

    /**
     * Auto-detect tingkat biaya berdasarkan eselon dan golongan
     */
    private function detectCostLevel($employeeData)
    {
        $eselon = strtoupper($employeeData['eselon'] ?? '');
        $golongan = strtoupper($employeeData['golongan'] ?? '');

        // Khusus untuk Kepala OPD/Badan (Eselon II)
        if ($employeeData['kepala_skpd'] == 'Y' || stripos($eselon, 'II') !== false) {
            return 'B'; // Tingkat B - Untuk Pejabat Eselon II
        }

        // Eselon IIb
        if (stripos($eselon, 'IIB') !== false || $eselon === 'II.B') {
            return 'C'; // Tingkat C - Untuk Pejabat Eselon IIb
        }

        // Eselon III
        if (stripos($eselon, 'III') !== false) {
            return 'D'; // Tingkat D - Untuk Pejabat Eselon III
        }

        // Eselon IV atau Golongan IV
        if (stripos($eselon, 'IV') !== false || stripos($golongan, 'IV') !== false) {
            return 'E'; // Tingkat E - Untuk Pejabat Eselon IV atau Golongan IV
        }

        // Golongan III atau II
        if (stripos($golongan, 'III') !== false || stripos($golongan, 'II') !== false) {
            return 'F'; // Tingkat F - Untuk Pejabat Golongan III dan II
        }

        // Golongan I
        if (stripos($golongan, 'I') !== false) {
            return 'G'; // Tingkat G - Untuk Pejabat Golongan I
        }

        // Default ke tingkat F jika tidak terdeteksi
        return 'F';
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

    public function selectOfficer($officerData)
    {
        $this->selectedOfficer = $officerData;
        $this->commanding_officer = $officerData['nama_lengkap'] ?? '';

        // Dispatch event to refresh Select2
        $this->dispatch('select2:refresh');
        $this->showOfficers = false;
    }

    public function updatedSelectedInstanceGiver($value)
    {
        // Dispatch event to refresh Select2 when value changes
        $this->dispatch('select2:refresh');
    }

    public function updatedSelectedInstance($value)
    {
        // Dispatch event to refresh Select2 when value changes
        $this->dispatch('select2:refresh');
    }

    public function updatedTransportation($value)
    {
        // Dispatch event to refresh Select2 when value changes
        $this->dispatch('select2:refresh');
    }

    public function updatedCostLevel($value)
    {
        // Dispatch event to refresh Select2 when value changes
        $this->dispatch('select2:refresh');
    }

    private function toastedValidation($keys = [])
    {
        $this->resetErrorBag();
        foreach ($keys as $key) {
            if ($key == 'selectedOfficer' && empty($this->selectedOfficer)) {
                LivewireAlert::title('Error')
                    ->text('Pejabat yang memberi perintah harus dipilih')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'pejabat';
                $this->addError('selectedOfficer', 'Pejabat yang memberi perintah harus dipilih');
                return false;
            }
            if ($key == 'selectedEmployee' && empty($this->selectedEmployee)) {
                LivewireAlert::title('Error')
                    ->text('Pegawai yang diperintahkan harus dipilih')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'pegawai';
                $this->addError('selectedEmployee', 'Pegawai yang diperintahkan harus dipilih');
                return false;
            }
            if ($key == 'sppd_number' && empty($this->sppd_number)) {
                LivewireAlert::title('Error')
                    ->text('Nomor SPPD harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'detail';
                $this->addError('sppd_number', 'Nomor SPPD harus diisi');
                return false;
            }

            if ($key == 'commanding_officer' && empty($this->commanding_officer)) {
                LivewireAlert::title('Error')
                    ->text('Pejabat yang memberi perintah harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'pejabat';
                $this->addError('commanding_officer', 'Pejabat yang memberi perintah harus diisi');
                return false;
            }

            if ($key == 'purpose' && empty($this->purpose)) {
                LivewireAlert::title('Error')
                    ->text('Maksud perjalanan dinas harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'detail';
                $this->addError('purpose', 'Maksud perjalanan dinas harus diisi');
                return false;
            }

            if ($key == 'transportation' && empty($this->transportation)) {
                LivewireAlert::title('Error')
                    ->text('Alat angkut yang dipergunakan harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'detail';
                $this->addError('transportation', 'Alat angkut yang dipergunakan harus diisi');
                return false;
            }

            if ($key == 'starting_place' && empty($this->starting_place)) {
                LivewireAlert::title('Error')
                    ->text('Tempat berangkat harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'detail';
                $this->addError('starting_place', 'Tempat berangkat harus diisi');
                return false;
            }

            if ($key == 'destination_places' && empty($this->destination_places)) {
                LivewireAlert::title('Error')
                    ->text('Tempat tujuan harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'detail';
                $this->addError('destination_places', 'Tempat tujuan harus diisi');
                return false;
            }

            if ($key == 'departure_date' && empty($this->departure_date)) {
                LivewireAlert::title('Error')
                    ->text('Tanggal berangkat harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'detail';
                $this->addError('departure_date', 'Tanggal berangkat harus diisi');
                return false;
            }

            if ($key == 'return_date' && empty($this->return_date)) {
                LivewireAlert::title('Error')
                    ->text('Tanggal harus kembali harus diisi')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'detail';
                $this->addError('return_date', 'Tanggal harus kembali harus diisi');
                return false;
            }

            if ($key == 'cost_level' && empty($this->cost_level)) {
                LivewireAlert::title('Error')
                    ->text('Tingkat biaya harus dipilih')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'biaya';
                $this->addError('cost_level', 'Tingkat biaya harus dipilih');
                return false;
            }

            if ($key == 'kodeRekening' && empty($this->kodeRekening)) {
                LivewireAlert::title('Error')
                    ->text('Kode rekening harus dipilih')
                    ->position('top-end')
                    ->timer(3000)
                    ->error()
                    ->toast()
                    ->show();
                $this->currentTab = 'biaya';
                $this->addError('kodeRekening', 'Kode rekening harus dipilih');
                return false;
            }
        }
    }

    public function save()
    {
        if ($this->toastedValidation([
            'selectedOfficer',
            'selectedEmployee',
            'sppd_number',
            'commanding_officer',
            'purpose',
            'transportation',
            'starting_place',
            'destination_places',
            'departure_date',
            'return_date',
            'cost_level',
            'kodeRekening',
        ]) === false) {
            return;
        }
        // $this->validate();

        DB::beginTransaction();
        try {
            $lamaPerjalanan = null;
            if ($this->departure_date && $this->return_date) {
                $start = \Carbon\Carbon::parse($this->departure_date);
                $end = \Carbon\Carbon::parse($this->return_date);
                $lamaPerjalanan = $start->diffInDays($end) + 1; // +1 untuk menghitung hari pertama
            }

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

            if ($this->selectedEmployee) {
                $employeeExecutor = Employee::where('nip', $this->selectedEmployee['nip'])->first();
                if (!$employeeExecutor) {
                    $employeeExecutor = Employee::create([
                        'semesta_id' => $this->selectedEmployee['id'],
                        'nama_lengkap' => $this->selectedEmployee['nama_lengkap'] ?? '',
                        'nip' => $this->selectedEmployee['nip'] ?? '',
                        'jenis_pegawai' => $this->selectedEmployee['jenis_pegawai'] ?? '',
                        'instance_id' => $this->selectedInstance,
                        'id_skpd' => $this->selectedEmployee['id_skpd'] ?? null,
                        'id_jabatan' => $this->selectedEmployee['id_jabatan'] ?? null,
                        'jabatan' => $this->selectedEmployee['jabatan'] ?? '',
                        'kepala_skpd' => $this->selectedEmployee['kepala_skpd'] ?? 'N',
                        'foto_pegawai' => $this->selectedEmployee['foto_pegawai'] ?? null,
                        'email' => $this->selectedEmployee['email'] ?? null,
                        'no_hp' => $this->selectedEmployee['no_hp'] ?? null,
                        'golongan' => $this->selectedEmployee['golongan'] ?? null,
                        'pangkat' => $this->selectedEmployee['pangkat'] ?? null,
                    ]);
                } else {
                    // Update instance if changed
                    if ($employeeExecutor->instance_id != $this->selectedInstance) {
                        $employeeExecutor->instance_id = $this->selectedInstance;
                        $employeeExecutor->save();
                    }
                }
            }

            $data = [
                'nomor_sppd' => $this->sppd_number,
                'instance_id' => $this->selectedInstance,
                'employee_giver_id' => $employeeOfficer->id ?? null,
                'employee_executor_id' => $employeeExecutor->id ?? null,
                'tingkat_biaya' => $this->cost_level,
                'maksud_perjalanan' => $this->purpose,
                'alat_angkutan' => $this->transportation,
                'tempat_berangkat' => $this->starting_place,
                'tempat_tujuan' => $this->destination_places,
                'lama_perjalanan' => $lamaPerjalanan,
                'tanggal_berangkat' => $this->departure_date,
                'tanggal_pulang' => $this->return_date,
                'instance_pembebanan_id' => $this->selectedInstance, // sementara samakan dengan instance pelaksana
                'kode_sub_kegiatan' => $this->subKegiatanData['fullcode'] ?? null,
                'uraian_sub_kegiatan' => $this->subKegiatanData['name'] ?? null,
                'anggaran_sub_kegiatan' => $this->subKegiatanData['pagu_induk'] ?? null,
                'kode_rekening' => $this->kodeRekeningData['fullcode'] ?? null,
                'uraian_rekening' => $this->kodeRekeningData['name'] ?? null,
                'anggaran_rekening' => $this->kodeRekeningData['pagu_induk'] ?? null,
                // 'keterangan_lain' => null,
                'publication_date' => $this->issued_date,
                'publication_place' => $this->department_head,
                // 'publication_employee_id' => null,
                // 'status' => 'approved',
                // 'created_by' => auth()->id(),
            ];

            if ($this->isEdit) {
                $sppd = SPPD::findOrFail($this->sppdId);
                $sppd->update($data);
                $message = 'SPPD berhasil diperbarui';
            } else {
                $data['status'] = 'approved';
                $data['created_by'] = auth()->id();
                SPPD::create($data);
                $message = 'SPPD berhasil dibuat';
            }

            LivewireAlert::title('Berhasil')
                ->text($message)
                ->position('top-end')
                ->timer(3000)
                ->success()
                ->toast()
                ->show();

            DB::commit();
            if ($this->isEdit == false) {
                return redirect()->route('admin.sppd.index');
            }
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('Terjadi kesalahan: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->toast()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.admin.sppd-form');
    }
}
