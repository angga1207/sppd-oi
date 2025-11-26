<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sppd;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SppdReports extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $statusFilter = '';
    public $instanceFilter = '';
    public $search = '';
    public $perPage = 10;
    public $sortColumn = '';
    public $sortDirection = 'asc';

    // Chart data
    public $monthlyData = [];
    public $statusData = [];
    public $instanceData = [];
    public $costLevelData = [];

    // Chart data for frontend
    public $monthlyTrendsData = [];
    public $statusDistributionData = [];
    public $instanceComparisonData = [];

    // Stats
    public $totalSppd = 0;
    public $totalBudgetUsed = 0;
    public $avgTripDuration = 0;
    public $currentMonthSppd = 0;

    // Table data
    public $reportData = [];
    public $instances = [];

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Set default date range (current month)
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');

        // Load instances for filter dropdown
        $this->instances = \App\Models\Instance::all();

        // Load initial data
        $this->loadReportData();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
        $this->loadReportData();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
        $this->loadReportData();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->loadReportData();
    }

    public function updatedInstanceFilter()
    {
        $this->resetPage();
        $this->loadReportData();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function loadReportData()
    {
        $query = Sppd::query()
            ->with(['employeeExecutor', 'instance'])
            ->whereBetween('tanggal_berangkat', [$this->dateFrom, $this->dateTo]);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->instanceFilter) {
            $query->where('instance_id', $this->instanceFilter);
        }

        $sppds = $query->get();

        // Calculate stats
        $this->totalSppd = $sppds->count();
        $this->totalBudgetUsed = $sppds->sum('anggaran_rekening') ?? 0; // Using anggaran_rekening as budget
        $this->avgTripDuration = $sppds->avg(function ($sppd) {
            if ($sppd->tanggal_berangkat && $sppd->tanggal_pulang) {
                return \Carbon\Carbon::parse($sppd->tanggal_berangkat)->diffInDays(\Carbon\Carbon::parse($sppd->tanggal_pulang)) + 1;
            }
            return $sppd->lama_perjalanan ?? 0;
        }) ?? 0;
        $this->currentMonthSppd = Sppd::whereMonth('tanggal_berangkat', Carbon::now()->month)
            ->whereYear('tanggal_berangkat', Carbon::now()->year)
            ->count();

        // Prepare chart data
        $this->prepareChartData($sppds);

        // Prepare table data
        $this->reportData = $sppds->map(function ($sppd) {
            return [
                'id' => $sppd->id,
                'sppd_number' => $sppd->nomor_sppd ?? 'N/A',
                'employee_name' => $sppd->employeeExecutor->nama_lengkap ?? 'N/A',
                'employee_nip' => $sppd->employeeExecutor->nip ?? 'N/A',
                'purpose' => $sppd->maksud_perjalanan ?? 'N/A',
                'starting_place' => $sppd->tempat_berangkat ?? 'N/A',
                'destination_places' => $sppd->tempat_tujuan ?? 'N/A',
                'departure_date' => $sppd->tanggal_berangkat ? Carbon::parse($sppd->tanggal_berangkat)->format('d/m/Y') : 'N/A',
                'return_date' => $sppd->tanggal_pulang ? Carbon::parse($sppd->tanggal_pulang)->format('d/m/Y') : 'N/A',
                'duration' => $sppd->lama_perjalanan ?? 0,
                'transportation' => $sppd->alat_angkutan ?? 'N/A',
                'cost_level' => $sppd->tingkat_biaya ?? 'N/A',
                'estimated_cost' => $sppd->anggaran_rekening ?? 0,
                'sub_kegiatan' => $sppd->uraian_sub_kegiatan ?? 'N/A',
                'kode_rekening' => $sppd->kode_rekening ?? 'N/A',
                'status' => $sppd->status ?? 'draft',
                'created_at' => Carbon::parse($sppd->created_at)->format('d/m/Y H:i'),
            ];
        })->toArray();
    }

    public function prepareChartData($sppds)
    {
        // Monthly Trends Chart (Line Chart)
        $monthlyData = collect();
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            $count = $sppds->filter(function ($sppd) use ($month) {
                return Carbon::parse($sppd->tanggal_berangkat)->isSameMonth($month);
            })->count();

            $monthlyData->prepend([
                'month' => $month->format('M Y'),
                'count' => $count
            ]);
        }

        $this->monthlyTrendsData = [
            'labels' => $monthlyData->pluck('month')->toArray(),
            'data' => $monthlyData->pluck('count')->toArray()
        ];

        // Status Distribution (Pie Chart)
        $statusData = $sppds->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        $this->statusDistributionData = [
            'labels' => $statusData->keys()->toArray(),
            'data' => $statusData->values()->toArray(),
            'colors' => ['#0C2B4E', '#1A3D64', '#FF6B6B', '#4ECDC4', '#45B7D1']
        ];

        // Instance Comparison (Bar Chart)
        $instanceData = $sppds->groupBy(function ($sppd) {
            return $sppd->instance->name ?? 'Unknown';
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'cost' => $group->sum('anggaran_rekening') ?? 0
            ];
        });

        $this->instanceComparisonData = [
            'labels' => $instanceData->keys()->toArray(),
            'count_data' => $instanceData->pluck('count')->toArray(),
            'cost_data' => $instanceData->pluck('cost')->toArray()
        ];
    }

    public function getPaginatedDataProperty()
    {
        $query = Sppd::query()
            ->with(['employeeExecutor', 'instance'])
            ->whereBetween('tanggal_berangkat', [$this->dateFrom, $this->dateTo]);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->instanceFilter) {
            $query->where('instance_id', $this->instanceFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nomor_sppd', 'like', '%' . $this->search . '%')
                    ->orWhereHas('employeeExecutor', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('nip', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('maksud_perjalanan', 'like', '%' . $this->search . '%')
                    ->orWhere('tempat_berangkat', 'like', '%' . $this->search . '%')
                    ->orWhere('tempat_tujuan', 'like', '%' . $this->search . '%')
                    ->orWhere('transportasi', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->sortColumn) {
            $column = $this->sortColumn;
            if ($column === 'employee_name') {
                $query->join('employees', 'sppds.employee_id', '=', 'employees.id')
                    ->orderBy('employees.name', $this->sortDirection)
                    ->select('sppds.*');
            } elseif ($column === 'sppd_number') {
                $query->orderBy('nomor_sppd', $this->sortDirection);
            } elseif ($column === 'purpose') {
                $query->orderBy('maksud_perjalanan', $this->sortDirection);
            } elseif ($column === 'estimated_cost') {
                $query->orderBy('anggaran_rekening', $this->sortDirection);
            } else {
                $query->orderBy($column, $this->sortDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $sppds = $query->paginate($this->perPage);

        // Transform data for display
        $sppds->getCollection()->transform(function ($sppd) {
            return [
                'sppd_number' => $sppd->nomor_sppd,
                'employee_name' => $sppd->employeeExecutor->nama_lengkap ?? 'N/A',
                'employee_nip' => $sppd->employeeExecutor->nip ?? 'N/A',
                'purpose' => $sppd->maksud_perjalanan,
                'starting_place' => $sppd->tempat_berangkat,
                'destination_places' => $sppd->tempat_tujuan,
                'departure_date' => Carbon::parse($sppd->tanggal_berangkat)->isoFormat('dddd, D MMMM YYYY'),
                'return_date' => $sppd->tanggal_pulang ? Carbon::parse($sppd->tanggal_pulang)->isoFormat('dddd, D MMMM YYYY') : '-',
                'duration' => $sppd->lama_perjalanan ??
                    ($sppd->tanggal_pulang ?
                        Carbon::parse($sppd->tanggal_berangkat)->diffInDays(Carbon::parse($sppd->tanggal_pulang)) + 1 :
                        0),
                'transportation' => $sppd->alat_angkutan,
                'sub_kegiatan' => $sppd->kode_sub_kegiatan . ' - ' . $sppd->uraian_sub_kegiatan ?? 'N/A',
                'kode_rekening' => $sppd->rekening . ' - ' . $sppd->rekening ?? 'N/A',
                'estimated_cost' => $sppd->anggaran_rekening ?? 0,
                'status' => $sppd->status,
            ];
        });

        // dd($sppds);

        return $sppds;
    }

    public function exportExcel()
    {
        // Implementation for Excel export
        return response()->download(storage_path('app/public/sppd_report.xlsx'));
    }

    public function resetFilters()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->statusFilter = '';
        $this->instanceFilter = '';
        $this->search = '';
        $this->sortColumn = '';
        $this->sortDirection = 'asc';
        $this->resetPage();
        $this->loadReportData();
    }

    public function render()
    {
        $paginatedData = $this->paginatedData;

        return view('livewire.admin.reports.sppd-reports', [
            'paginatedData' => $paginatedData
        ]);
    }
}
