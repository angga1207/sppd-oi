<?php

namespace App\Livewire\Admin\SuratPerintah;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Instance;
use App\Models\SuratPerintah;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\SPPD as ModelsSPPD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('components.layouts.app')]
#[Title('Manajemen SPPD')]
class Sppd extends Component
{
    public $dataId;
    public $dataSuratPerintah;
    public $instanceId = '';
    public $instanceSelected = '';
    public $searchEmployee = '';
    public $rawSemestaUsers = [];
    public $semestaUsers = [];
    public $showEmployees = false;
    public $selectedEmployee = null;
    public $sppd_number = '';
    public $sppds = [];

    public $availableYears;
    public $availableMonths;
    public $selectedYear;
    public $selectedMonth;

    public $isLoadedKodeRekening = false;
    public $kodeRekening = null;
    public $kodeRekeningData = null;
    public $subKegiatan = null;
    public $subKegiatanData = null;
    public $arrSubKegiatan = [];
    public $arrKodeRekening = [];

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

    function mount($id)
    {
        if (!$id) {
            return redirect()->route('admin.surat-perintah.index');
        }
        $this->dataId = $id;
        $this->dataSuratPerintah = SuratPerintah::find($id);
        $this->instanceId = $this->dataSuratPerintah ? $this->dataSuratPerintah->instance_id : '';
        $this->instanceSelected = $this->instanceId;
        $this->loadSppds();


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
    }

    public function fetchSemestaUsers()
    {
        $this->rawSemestaUsers = [];  // Store unfiltered users
        $this->searchEmployee = '';       // Search query for employees
        $this->semestaUsers = [];
        $this->showEmployees = false;

        // reset SubKegiatan & Kode Rekening
        $this->subKegiatan = null;
        $this->subKegiatanData = null;
        $this->kodeRekening = null;
        $this->kodeRekeningData = null;

        if (!$this->instanceSelected) {
            LivewireAlert::title('Peringatan')
                ->text('Silakan pilih instansi terlebih dahulu')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        $instance = Instance::find($this->instanceSelected);

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
                $dataSemestaUsers = $response->json()['data'] ?? [];
                $dataSemestaUsers = collect($dataSemestaUsers);
                $this->rawSemestaUsers = $dataSemestaUsers;
                // dd($this->dataSuratPerintah);
                if (!$this->dataSuratPerintah->employee_giver_instance_id) {
                    $dataSemestaUsers = $dataSemestaUsers->where('kepala_skpd', 'Y');
                    $dataSemestaUsers = $dataSemestaUsers->values()->toArray();
                } else if ($this->dataSuratPerintah->instance_id) {
                    $employeeGiver = $this->dataSuratPerintah->employeeGiver;
                    $dataSemestaUsers = $dataSemestaUsers->whereNotIn('id', $employeeGiver ? [$employeeGiver->semesta_id] : []);
                    $dataSemestaUsers = $dataSemestaUsers->values()->toArray();
                } else {
                    $dataSemestaUsers = $dataSemestaUsers->where('kepala_skpd', 'Y');
                    $dataSemestaUsers = $dataSemestaUsers->values()->toArray();
                }

                $this->semestaUsers = $dataSemestaUsers;
                // $this->rawSemestaUsers = $this->semestaUsers;
                // dd($this->rawSemestaUsers);

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

    public function toggleShowEmployees()
    {
        $this->showEmployees = ! $this->showEmployees;
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
        $this->showEmployees = true;
    }

    public function selectEmployee($employeeData)
    {
        $this->selectedEmployee = $employeeData;
        // Dispatch event to refresh Select2
        $this->dispatch('select2:refresh');
        $this->showEmployees = false;
    }

    public function confirmAddEmployee()
    {
        // dd($this->kodeRekeningData, $this->subKegiatanData);
        if (!$this->selectedEmployee) {
            LivewireAlert::title('Peringatan')
                ->text('Silakan pilih pegawai terlebih dahulu')
                ->warning()
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->show();
            return;
        }

        $this->validate(
            [
                'sppd_number' => 'required|string|unique:sppd,nomor_sppd',
                'kodeRekening' => 'required',
                'subKegiatan' => 'required',
            ],
            [],
            [
                'sppd_number' => 'Nomor SPPD',
                'kodeRekening' => 'Kode Rekening',
                'subKegiatan' => 'Sub Kegiatan',
            ]
        );

        // check if selected employee already has SPPD for this Surat Perintah
        $existingSppd = ModelsSPPD::where('surat_perintah_id', $this->dataSuratPerintah->id)
            ->where('employee_executor_id', function ($query) {
                $query->select('id')
                    ->from('employees')
                    ->where('nip', $this->selectedEmployee['nip']);
            })
            ->first();
        if ($existingSppd) {
            LivewireAlert::title('Peringatan')
                ->text('Pegawai yang dipilih sudah memiliki SPPD untuk Surat Perintah Tugas ini')
                ->warning()
                ->show();
            return;
        }

        // update/create employee data
        if ($this->selectedEmployee && $this->selectedEmployee['nip']) {
            // FOR ORIGINAL OFFICER DATA
            $employee = Employee::where('nip', $this->selectedEmployee['nip'])->first();
            $instance = Instance::where('id_eoffice', $this->selectedEmployee['id_skpd'] ?? null)->first();
            if (!$employee) {
                $employee = Employee::create([
                    'semesta_id' => $this->selectedEmployee['id'],
                    'nama_lengkap' => $this->selectedEmployee['nama_lengkap'] ?? '',
                    'nip' => $this->selectedEmployee['nip'] ?? '',
                    'jenis_pegawai' => $this->selectedEmployee['jenis_pegawai'] ?? '',
                    'instance_id' => $instance ? $instance->id : $this->dataSuratPerintah->instance_id,
                    'id_skpd' => $this->selectedEmployee['id_skpd'] ?? null,
                    'id_jabatan' => $this->selectedEmployee['id_jabatan'] ?? null,
                    'jabatan' => $this->selectedEmployee['jabatan'] ?? '',
                    'kepala_skpd' => $this->selectedEmployee['kepala_skpd'] ?? 'N',
                    'foto_pegawai' => $this->selectedEmployee['foto_pegawai'] ?? null,
                    'email' => $this->selectedEmployee['email'] ?? null,
                    'no_hp' => $this->selectedEmployee['no_hp'] ?? null,
                    'eselon' => $this->selectedEmployee['eselon'] ?? null,
                    'golongan' => $this->selectedEmployee['golongan'] ?? null,
                    'pangkat' => $this->selectedEmployee['pangkat'] ?? null,
                ]);
            } else {
                // Update instance if changed
                if ($employee->instance_id != $this->dataSuratPerintah->instance_id) {
                    $employee->instance_id = $this->dataSuratPerintah->instance_id;
                    $employee->save();
                }
            }
        }

        if (!$employee) {
            LivewireAlert::title('Gagal')
                ->text('Gagal menemukan atau membuat data pegawai.')
                ->position('top-end')
                ->timer(3000)
                ->error()
                ->toast()
                ->show();
            return;
        }

        // Auto-detect cost level based on eselon and golongan
        $detectedLevel = $this->detectCostLevel($employee);

        $sppd = [
            'id' => null,
            'nomor_sppd' => $this->sppd_number ?? null,
            'surat_perintah_id' => $this->dataSuratPerintah->id,
            'instance_id' => $this->dataSuratPerintah->instance_id,
            'employee_giver_id' => $this->dataSuratPerintah->employee_giver_id,
            'employee_giver_instance_id' => $this->dataSuratPerintah->employee_giver_instance_id,
            'employee_executor_id' => $employee->id ?? null,
            'employee_executor_instance_id' => $employee->instance_id ?? null,
            'tingkat_biaya' => $detectedLevel ?? null,
            'maksud_perjalanan' => $this->dataSuratPerintah->tujuan,
            'alat_angkutan' => $this->dataSuratPerintah->alat_angkutan,
            'tempat_berangkat' => $this->dataSuratPerintah->tempat_berangkat,
            'tempat_tujuan' => $this->dataSuratPerintah->tempat_tujuan,
            'province_id' => $this->dataSuratPerintah->province_id,
            'regency_id' => $this->dataSuratPerintah->regency_id,
            'lama_perjalanan' => $this->dataSuratPerintah->lama_perjalanan,
            'tanggal_berangkat' => $this->dataSuratPerintah->tanggal_berangkat,
            'tanggal_pulang' => $this->dataSuratPerintah->tanggal_pulang,
            'instance_pembebanan_id' => $this->dataSuratPerintah->instance_id,
            'kode_sub_kegiatan' => '',
            'uraian_sub_kegiatan' => '',
            'anggaran_sub_kegiatan' => 0,
            'kode_rekening' => '',
            'uraian_rekening' => '',
            'anggaran_rekening' => 0,
            'keterangan_lain' => '',
            'publication_date' => $this->dataSuratPerintah->publication_date,
            'publication_place' => $this->dataSuratPerintah->publication_place,
            'publication_employee_id' => $this->dataSuratPerintah->publication_employee_id ?? $this->dataSuratPerintah->employee_giver_id,
            'status' => 'draft', // approved, rejected, draft
            'created_by' => auth()->user()->id,


            'kode_sub_kegiatan' => $this->subKegiatanData['fullcode'] ?? null,
            'uraian_sub_kegiatan' => $this->subKegiatanData['name'] ?? null,
            'anggaran_sub_kegiatan' => $this->subKegiatanData['pagu_induk'] ?? null,
            'kode_rekening' => $this->kodeRekeningData['fullcode'] ?? null,
            'uraian_rekening' => $this->kodeRekeningData['name'] ?? null,
            'anggaran_rekening' => $this->kodeRekeningData['pagu_induk'] ?? null,
        ];
        // dd($sppd);

        // Create SPPD record in database
        DB::beginTransaction();
        try {
            ModelsSPPD::create([
                ...$sppd,
            ]);
            DB::commit();
            LivewireAlert::title('Berhasil')
                ->text('Pegawai berhasil ditambahkan ke SPPD')
                ->position('top-end')
                ->timer(3000)
                ->success()
                ->toast()
                ->show();

            // Reload SPPD list
            $this->loadSppds();

            $this->sppd_number = null;
            $this->selectedEmployee = null;
            $this->showEmployees = true;
            // $this->instanceSelected = $this->instanceId ?? null;
            // $this->semestaUsers = [];
            // $this->rawSemestaUsers = [];
            // $this->searchEmployee = '';
            $this->dispatch('select2:refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            LivewireAlert::title('Gagal')
                ->text('Gagal menambahkan pegawai ke SPPD: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->error()
                ->toast()
                ->show();
        }
    }

    public function deleteSppd($sppdId)
    {
        if ($this->dataSuratPerintah->status == 'approved') {
            LivewireAlert::title('Gagal')
                ->text('SPPD tidak dapat dihapus karena Surat Perintah sudah ditandatangani')
                ->position('top-end')
                ->timer(3000)
                ->error()
                ->toast()
                ->show();
            return;
        }
        $sppd = ModelsSPPD::find($sppdId);
        if (!$sppd) {
            LivewireAlert::title('Gagal')
                ->text('SPPD tidak ditemukan')
                ->position('top-end')
                ->timer(3000)
                ->error()
                ->toast()
                ->show();
            return;
        }

        DB::beginTransaction();
        try {
            $sppd->delete();
            DB::commit();
            LivewireAlert::title('Berhasil')
                ->text('SPPD berhasil dihapus')
                ->position('top-end')
                ->timer(3000)
                ->success()
                ->toast()
                ->show();

            // Reload SPPD list
            $this->loadSppds();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Gagal')
                ->text('Gagal menghapus SPPD: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->error()
                ->toast()
                ->show();
        }
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

    function loadSppds()
    {
        $this->sppds = ModelsSPPD::where('surat_perintah_id', $this->dataSuratPerintah->id)
            ->with(['employeeExecutor', 'employeeGiver', 'instance', 'instancePembebanan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function updated($field)
    {
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


    public function fetchKodeRekening()
    {
        $this->isLoadedKodeRekening = false;
        if ($this->instanceSelected === null) {
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
                'instance_id' => $this->instanceSelected,
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

    public function render()
    {
        // $instances = Instance::when($this->instanceId, function ($query) {
        //     $query->where('id', $this->instanceId);
        // })
        //     ->orderBy('name')
        //     ->get();

        // dd($this->dataSuratPerintah);
        $instances = Instance::when($this->dataSuratPerintah['employee_giver_instance_id'], function ($query) {
            $query->where('id', $this->dataSuratPerintah['employee_giver_instance_id']);
        })
            ->orderBy('name')
            ->get();
        return view('livewire.admin.surat-perintah.sppd', compact('instances'));
    }
}
