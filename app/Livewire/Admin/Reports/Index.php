<?php

namespace App\Livewire\Admin\Reports;

use Carbon\Carbon;
use App\Models\SPPD;
use Livewire\Component;
use App\Models\Instance;
use App\Models\SuratPerintah;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Laporan SPT & SPPD')]
class Index extends Component
{
    // Filter properties
    public $reportType = 'spt'; // spt or sppd
    public $startDate;
    public $endDate;
    public $instanceFilter = '';
    public $statusFilter = '';
    public $exportFormat = 'excel'; // excel or pdf

    // Data properties
    public $instances = [];
    public $statistics = [];
    public $chartData = [];

    public function mount()
    {
        // Set default date range (current month)
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        // Load instances for filter
        $this->loadInstances();

        // Load initial statistics
        $this->loadSptStatistics();
    }

    public function loadInstances()
    {
        $this->instances = Instance::when(auth()->user()->instance_id, function ($query) {
            $query->where('id', auth()->user()->instance_id);
        })
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function updatedReportType()
    {
        $this->statusFilter = '';
        $this->generateReport();
    }

    public function updatedStartDate()
    {
        if ($this->startDate && $this->endDate) {
            $this->generateReport();
        }
    }

    public function updatedEndDate()
    {
        if ($this->startDate && $this->endDate) {
            $this->generateReport();
        }
    }

    public function updatedInstanceFilter()
    {
        $this->generateReport();
    }

    public function updatedStatusFilter()
    {
        $this->generateReport();
    }

    public function changeReportType($type)
    {
        $this->reportType = $type;
        $this->statusFilter = '';
        $this->generateReport();
    }

    public function generateReport()
    {
        if (!$this->startDate || !$this->endDate) {
            return;
        }

        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        if ($this->reportType === 'spt') {
            $this->loadSptStatistics();
        } else {
            $this->loadSppdStatistics();
        }

        // Dispatch event to refresh chart
        $this->dispatch('reportGenerated');
    }

    private function loadSptStatistics()
    {
        $query = SuratPerintah::query()
            ->with(['instance', 'employeeGiver', 'sppds'])
            ->whereBetween('tanggal_berangkat', [$this->startDate, $this->endDate]);

        if ($this->instanceFilter) {
            $query->where('instance_id', $this->instanceFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $data = $query->get();

        $this->statistics = [
            'total' => $data->count(),
            'draft' => $data->where('status', 'draft')->count(),
            'sent' => $data->where('status', 'sent')->count(),
            'approved' => $data->where('status', 'approved')->count(),
            'rejected' => $data->where('status', 'rejected')->count(),
            'completed' => $data->where('status', 'completed')->count(),
            'total_sppd' => $data->sum(fn($item) => $item->sppds->count()),
            'by_instance' => $data->groupBy('instance_id')->map(function ($items) {
                return [
                    'name' => $items->first()->instance->name ?? 'Bupati Ogan Ilir',
                    'count' => $items->count(),
                ];
            })->values(),
            'by_month' => $data->groupBy(function ($item) {
                return Carbon::parse($item->tanggal_berangkat)->format('Y-m');
            })->map(function ($items, $month) {
                return [
                    'month' => Carbon::parse($month)->isoFormat('MMMM Y'),
                    'count' => $items->count(),
                ];
            })->values(),
        ];

        $this->chartData = [
            'status' => [
                'labels' => ['Draft', 'Menunggu Tanda Tangan', 'Ditandatangani', 'Ditolak'],
                'data' => [
                    $this->statistics['draft'],
                    $this->statistics['sent'],
                    $this->statistics['approved'],
                    $this->statistics['rejected'],
                ],
            ],
        ];
        // dd($this->chartData);
    }

    private function loadSppdStatistics()
    {
        $query = SPPD::query()
            ->with(['instance', 'employeeExecutor', 'employeeGiver', 'suratPerintah'])
            ->whereBetween('tanggal_berangkat', [$this->startDate, $this->endDate]);

        if ($this->instanceFilter) {
            $query->where('instance_id', $this->instanceFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $data = $query->get();

        // Calculate total biaya
        $totalBiaya = $data->sum(function ($sppd) {
            return ($sppd->anggaran_sub_kegiatan ?? 0) +
                ($sppd->uang_harian ?? 0) +
                ($sppd->biaya_penginapan ?? 0) +
                ($sppd->uang_representasi ?? 0) +
                ($sppd->transport_pp ?? 0);
        });

        $this->statistics = [
            'total' => $data->count(),
            'draft' => $data->where('status', 'draft')->count(),
            'sent' => $data->where('status', 'sent')->count(),
            'approved' => $data->where('status', 'approved')->count(),
            'rejected' => $data->where('status', 'rejected')->count(),
            'completed' => $data->where('status', 'completed')->count(),
            'total_biaya' => $totalBiaya,
            'avg_duration' => $data->avg('lama_perjalanan') ?? 0,
            'by_instance' => $data->groupBy('instance_id')->map(function ($items) {
                return [
                    'name' => $items->first()->instance->name ?? 'Bupati Ogan Ilir',
                    'count' => $items->count(),
                ];
            })->values(),
            'by_tingkat' => $data->groupBy('tingkat_biaya')->map(function ($items, $tingkat) {
                return [
                    'tingkat' => $tingkat ?: 'Tidak Ada',
                    'count' => $items->count(),
                ];
            })->values(),
            'by_month' => $data->groupBy(function ($item) {
                return Carbon::parse($item->tanggal_berangkat)->format('Y-m');
            })->map(function ($items, $month) {
                return [
                    'month' => Carbon::parse($month)->isoFormat('MMMM Y'),
                    'count' => $items->count(),
                ];
            })->values(),
        ];

        $this->chartData = [
            'status' => [
                'labels' => ['Draft', 'Menunggu Tanda Tangan', 'Ditandatangani', 'Ditolak'],
                'data' => [
                    $this->statistics['draft'],
                    $this->statistics['sent'],
                    $this->statistics['approved'],
                    $this->statistics['rejected'],
                ],
            ],
        ];
    }

    public function exportReport()
    {
        // This will be handled by a separate export action
        $this->dispatch('export-report', [
            'type' => $this->reportType,
            'format' => $this->exportFormat,
            'filters' => [
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'instance' => $this->instanceFilter,
                'status' => $this->statusFilter,
            ],
        ]);
    }

    public function resetFilters()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->instanceFilter = '';
        $this->statusFilter = '';
        $this->statistics = [];
        $this->chartData = [];

        $this->dispatch('filtersReset');
    }

    public function render()
    {
        return view('livewire.admin.reports.index');
    }
}
