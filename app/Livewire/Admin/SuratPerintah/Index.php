<?php

namespace App\Livewire\Admin\SuratPerintah;

use Livewire\Component;
use App\Models\Instance;
use Livewire\WithPagination;
use App\Models\SuratPerintah;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('components.layouts.app')]
#[Title('Daftar Surat Perintah')]

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $instanceFilter = '';
    public $dateFilter = '';
    public $perPage = 10;

    public $selectedSppdForSign;
    public $showSignModal = false;
    public $signPassphrase = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'instanceFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
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

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'instanceFilter', 'dateFilter']);
        $this->resetPage();
        $this->dispatch('filtersReset');
    }

    public function openModalSign($suratPerintahId)
    {
        $this->selectedSppdForSign = SuratPerintah::find($suratPerintahId);
        $this->showSignModal = true;
    }

    public function closeModalSign()
    {
        $this->showSignModal = false;
        $this->signPassphrase = '';
        $this->selectedSppdForSign = null;
    }

    public function processDigitalSignature()
    {
        // Logic for processing digital signature goes here
        // You can access the selected Surat Perintah using $this->selectedSppdForSign

        $this->validate([
            'signPassphrase' => 'required|string',
        ], [], [
            'signPassphrase' => 'Passphrase Tanda Tangan Digital',
        ]);

        if (!$this->signPassphrase == 'password') {
            $this->addError('signPassphrase', 'Passphrase yang Anda masukkan salah.');
            return;
        }

        DB::beginTransaction();
        try {
            // Simulate digital signature processing
            SuratPerintah::where('id', $this->selectedSppdForSign['id'])
                ->update(['status' => 'approved']);
            DB::commit();

            // After processing, you might want to close the modal
            $this->signPassphrase = '';
            $this->selectedSppdForSign = null;
            $this->showSignModal = false;

            LivewireAlert::title('Sukses!')
                ->text('Tanda tangan digital telah diproses.')
                ->success()
                ->position('center')
                ->show();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error!')
                ->text('Terjadi kesalahan saat memproses tanda tangan digital. Silakan coba lagi.')
                ->error()
                ->position('center')
                ->show();
        }
    }

    public function exportExcel()
    {
        LivewireAlert::title('Info')
            ->text('Fitur ekspor ke Excel sedang dalam pengembangan.')
            ->position('top-end')
            ->timer(3000)
            ->toast()
            ->info()
            ->show();
        return;
    }

    public function render()
    {
        $datas = SuratPerintah::search($this->search)
            ->with(['employeeGiver', 'sppds', 'instance'])
            // ->when(auth()->user()->instance_id, function ($query) {
            //     $query->where('instance_id', auth()->user()->instance_id);
            // })
            ->when(auth()->user()->instance_id, function ($query) {
                if (auth()->user()->role_id == 2) {
                    $query->where('instance_id', auth()->user()->instance_id);
                } else {
                    $query->where(function ($q) {
                        $q->where('employee_giver_id', auth()->user()->id)
                            ->orWhere('publication_employee_id', auth()->user()->id)
                            ->orWhere('created_by', auth()->user()->id)
                            ->orWhereRelation('sppds', 'employee_executor_id', auth()->user()->employee_id);
                        //   ->orWhereHas('sppds', function ($sppdQuery) {
                        //       $sppdQuery->where('employee_executor_id', auth()->user()->id);
                        //   });
                    });
                }
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->instanceFilter, function ($query) {
                $query->where('instance_id', $this->instanceFilter);
            })
            ->when($this->dateFilter, function ($query) {
                $query->whereDate('publication_date', $this->dateFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $instances = Instance::orderBy('name')->get();

        return view('livewire.admin.surat-perintah.index', [
            'instances' => $instances,
            'datas' => $datas,
        ]);
    }
}
