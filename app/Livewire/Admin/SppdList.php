<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Sppd;
use App\Models\Instance;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Exports\SppdExport;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Daftar SPPD')]
class SppdList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $instanceFilter = '';
    public $startDateFilter = '';
    public $endDateFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'instanceFilter' => ['except' => ''],
        'startDateFilter' => ['except' => ''],
        'endDateFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingInstanceFilter()
    {
        $this->resetPage();
    }

    public function updatingStartDateFilter()
    {
        $this->resetPage();
    }

    public function updatingEndDateFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'instanceFilter', 'startDateFilter', 'endDateFilter']);
        $this->resetPage();
        $this->dispatch('filtersReset');
    }

    public function exportExcel()
    {
        try {
            $filters = [
                'search' => $this->search,
                'statusFilter' => $this->statusFilter,
                'instanceFilter' => $this->instanceFilter,
                'startDateFilter' => $this->startDateFilter,
                'endDateFilter' => $this->endDateFilter,
            ];

            $fileName = 'SPPD_Export_' . date('Y-m-d_His') . '.xlsx';

            LivewireAlert::title('Berhasil')
                ->text('File Excel sedang diunduh...')
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->success()
                ->show();

            return Excel::download(new SppdExport($filters), $fileName);
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('Gagal export Excel: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->toast()
                ->error()
                ->show();
        }
    }

    public function duplicateSppd($id)
    {
        try {
            $sppd = Sppd::findOrFail($id);

            // Store data di session untuk form create
            session([
                'duplicate_sppd_data' => [
                    'instance_id' => $sppd->instance_id,
                    'employee_giver_id' => $sppd->employee_giver_id,
                    'employee_executor_id' => $sppd->employee_executor_id,
                    'tingkat_biaya' => $sppd->tingkat_biaya,
                    'maksud_perjalanan' => $sppd->maksud_perjalanan,
                    'alat_angkutan' => $sppd->alat_angkutan,
                    'tempat_berangkat' => $sppd->tempat_berangkat,
                    'tempat_tujuan' => $sppd->tempat_tujuan,
                    'lama_perjalanan' => $sppd->lama_perjalanan,
                    'tanggal_berangkat' => $sppd->tanggal_berangkat,
                    'tanggal_pulang' => $sppd->tanggal_pulang,
                    'instance_pembebanan_id' => $sppd->instance_pembebanan_id,
                    'kode_rekening' => $sppd->kode_rekening,
                    'uraian_rekening' => $sppd->uraian_rekening,
                    'anggaran' => $sppd->anggaran,
                    'keterangan_lain' => $sppd->keterangan_lain,
                    'publication_date' => $sppd->publication_date,
                    'publication_place' => $sppd->publication_place,
                    'publication_employee_id' => $sppd->publication_employee_id,
                ]
            ]);

            return redirect()->route('admin.sppd.create');
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('Gagal menduplikasi SPPD: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->toast()
                ->error()
                ->show();
        }
    }

    public function viewDetail($id)
    {
        // TODO: Implement view detail modal or redirect to detail page
        LivewireAlert::title('Info')
            ->text('Detail SPPD akan ditampilkan di sini')
            ->position('top-end')
            ->timer(3000)
            ->toast()
            ->info()
            ->show();
    }

    public function deleteSppd($id)
    {
        try {
            $sppd = Sppd::findOrFail($id);
            $sppd->delete();

            LivewireAlert::title('Berhasil')
                ->text('SPPD berhasil dihapus')
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->success()
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('Gagal menghapus SPPD: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->toast()
                ->error()
                ->show();
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $sppd = Sppd::findOrFail($id);
            $sppd->update(['status' => $status]);

            LivewireAlert::title('Berhasil')
                ->text('Status SPPD berhasil diupdate')
                ->position('top-end')
                ->timer(3000)
                ->toast()
                ->success()
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('Gagal mengupdate status: ' . $e->getMessage())
                ->position('top-end')
                ->timer(5000)
                ->toast()
                ->error()
                ->show();
        }
    }

    public function render()
    {
        $sppds = Sppd::with(['employeeGiver', 'employeeExecutor', 'instance'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomor_sppd', 'like', '%' . $this->search . '%')
                        ->orWhere('tempat_tujuan', 'like', '%' . $this->search . '%')
                        ->orWhere('maksud_perjalanan_dinas', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employeeExecutor', function ($q) {
                            $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                                ->orWhere('nip', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('employeeGiver', function ($q) {
                            $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                                ->orWhere('nip', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->instanceFilter, function ($query) {
                $query->where('instance_id', $this->instanceFilter);
            })
            ->when($this->startDateFilter, function ($query) {
                $query->whereDate('tanggal_berangkat', '>=', $this->startDateFilter);
            })
            ->when($this->endDateFilter, function ($query) {
                $query->whereDate('tanggal_pulang', '<=', $this->endDateFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $instances = Instance::orderBy('name')->get();

        return view('livewire.admin.sppd-list', [
            'sppds' => $sppds,
            'instances' => $instances,
        ]);
    }
}
