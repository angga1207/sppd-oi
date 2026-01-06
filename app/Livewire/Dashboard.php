<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\SPPD;
use App\Models\Employee;
use App\Models\Instance;
use App\Models\SuratPerintah;
use Illuminate\Support\Facades\DB;
use Asantibanez\LivewireCharts\Models\AreaChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

#[Layout('components.layouts.app')]
#[Title('Dashboard - SPPD Admin')]


class Dashboard extends Component
{
    public $yearNow;
    public $arrYears = [];

    public function mount()
    {
        $this->yearNow = now()->year;
        for ($year = 2025; $year <= $this->yearNow; $year++) {
            $this->arrYears[] = $year;
        }
    }

    public function render()
    {
        // ========================================
        // SURAT PERINTAH TUGAS STATISTICS
        // ========================================
        $totalSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->count();

        $draftSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'draft')
            ->count();

        $sentSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'sent')
            ->count();

        $approvedSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'approved')
            ->count();

        $rejectedSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'rejected')
            ->count();

        // ========================================
        // SPPD STATISTICS
        // ========================================
        $totalSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->count();

        $draftSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'draft')
            ->count();

        $sentSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'sent')
            ->count();

        $approvedSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'approved')
            ->count();

        $rejectedSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->where('status', 'rejected')
            ->count();

        // ========================================
        // CURRENT MONTH STATISTICS
        // ========================================
        $currentMonthSpt = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', now()->year)
            ->whereMonth('publication_date', now()->month)
            ->count();

        $currentMonthSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', now()->year)
            ->whereMonth('publication_date', now()->month)
            ->count();

        // ========================================
        // ACTIVE TRIPS (Currently Ongoing)
        // ========================================
        $activeTrips = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->where('status', 'approved')
            ->where('tanggal_berangkat', '<=', now())
            ->where('tanggal_pulang', '>=', now())
            ->count();

        // ========================================
        // MONTHLY STATISTICS (Last 6 Months)
        // ========================================
        $monthlySuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->select(
                DB::raw('DATE_TRUNC(\'month\', created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $monthlySppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->select(
                DB::raw('DATE_TRUNC(\'month\', created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Build Area Chart for Monthly Surat Perintah
        $monthlySptChart = (new AreaChartModel())
            ->setTitle('Trend Surat Perintah Tugas')
            ->setAnimated(true)
            ->withOnPointClickEvent('onPointClick')
            ->setDataLabelsEnabled(true)
            ->setColors(['#1e3a8a'])
            ->setJsonConfig([
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'precision' => 0,
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'formatter' => 'function(value) { return Math.round(value); }'
                    ]
                ]
            ]);

        foreach ($monthlySuratPerintah as $data) {
            $monthName = \Carbon\Carbon::parse($data->month)->format('M Y');
            $monthlySptChart->addPoint($monthName, $data->total, '#1e3a8a');
        }

        // Build Area Chart for Monthly SPPD
        $monthlySppdChart = (new AreaChartModel())
            ->setTitle('Trend SPPD')
            ->setAnimated(true)
            ->withOnPointClickEvent('onPointClick')
            ->setDataLabelsEnabled(true)
            ->setColors(['#6366f1'])
            ->setJsonConfig([
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'precision' => 0,
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'formatter' => 'function(value) { return Math.round(value); }'
                    ]
                ]
            ]);

        foreach ($monthlySppd as $data) {
            $monthName = \Carbon\Carbon::parse($data->month)->format('M Y');
            $monthlySppdChart->addPoint($monthName, $data->total, '#6366f1');
        }

        // ========================================
        // MONTHLY TRIPS COUNT (Last 12 Months)
        // ========================================
        $monthlyTripsCount = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->select(
                DB::raw('DATE_TRUNC(\'month\', tanggal_berangkat) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal_berangkat', '>=', now()->subMonths(12))
            ->where('status', 'approved')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Build Column Chart for Monthly Trips Count
        $monthlyTripsChart = (new ColumnChartModel())
            ->setTitle('Jumlah Perjalanan Dinas per Bulan')
            ->setAnimated(true)
            ->withOnColumnClickEventName('onColumnClick')
            ->setDataLabelsEnabled(true)
            ->setColumnWidth(50)
            ->setColors(['#10b981'])
            ->setJsonConfig([
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'precision' => 0,
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'formatter' => 'function(value) { return Math.round(value); }'
                    ]
                ]
            ]);

        foreach ($monthlyTripsCount as $data) {
            $monthName = \Carbon\Carbon::parse($data->month)->isoFormat('MMM YYYY');
            $monthlyTripsChart->addColumn($monthName, $data->total, '#10b981');
        }

        // ========================================
        // MONTHLY COST REALIZATION (Dummy Data)
        // ========================================
        $monthlyCostData = collect([
            ['month' => now()->subMonths(11)->startOfMonth(), 'total_cost' => 45000000],
            ['month' => now()->subMonths(10)->startOfMonth(), 'total_cost' => 52000000],
            ['month' => now()->subMonths(9)->startOfMonth(), 'total_cost' => 48000000],
            ['month' => now()->subMonths(8)->startOfMonth(), 'total_cost' => 61000000],
            ['month' => now()->subMonths(7)->startOfMonth(), 'total_cost' => 55000000],
            ['month' => now()->subMonths(6)->startOfMonth(), 'total_cost' => 58000000],
            ['month' => now()->subMonths(5)->startOfMonth(), 'total_cost' => 67000000],
            ['month' => now()->subMonths(4)->startOfMonth(), 'total_cost' => 72000000],
            ['month' => now()->subMonths(3)->startOfMonth(), 'total_cost' => 69000000],
            ['month' => now()->subMonths(2)->startOfMonth(), 'total_cost' => 75000000],
            ['month' => now()->subMonths(1)->startOfMonth(), 'total_cost' => 81000000],
            ['month' => now()->startOfMonth(), 'total_cost' => 78000000],
        ]);

        // Build Line Chart for Monthly Cost Realization
        $monthlyCostChart = (new LineChartModel())
            ->setTitle('Realisasi Biaya Perjalanan Dinas per Bulan')
            ->setAnimated(true)
            ->withOnPointClickEvent('onPointClick')
            ->setDataLabelsEnabled(true)
            ->setColors(['#f59e0b'])
            ->setJsonConfig([
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'callback' => 'function(value) { return "Rp " + (value/1000000).toFixed(0) + "M"; }'
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'formatter' => 'function(value) { return "Rp " + (value/1000000).toFixed(0) + "M"; }'
                    ]
                ]
            ]);

        foreach ($monthlyCostData as $data) {
            $monthName = \Carbon\Carbon::parse($data['month'])->format('M Y');
            $monthlyCostChart->addPoint($monthName, $data['total_cost']);
        }

        // ========================================
        // SURAT PERINTAH BY INSTANCE
        // ========================================
        $suratPerintahByInstance = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->select('instance_id', DB::raw('COUNT(*) as total'))
            ->groupBy('instance_id')
            ->with('instance')
            ->get()
            ->map(function ($item) {
                return [
                    'instance_name' => $item->instance ? $item->instance->name : 'BUPATI',
                    'total' => $item->total
                ];
            });

        $suratPerintahByInstance = $suratPerintahByInstance->sortByDesc('total')->take(10)->values();

        // Build Column Chart for Surat Perintah by Instance
        $sptInstanceChart = (new ColumnChartModel())
            ->setTitle('Distribusi SPT')
            ->setAnimated(true)
            ->withOnColumnClickEventName('onColumnClick')
            ->setDataLabelsEnabled(true)
            ->setColumnWidth(50)
            ->setColors(['#1e3a8a'])
            ->setJsonConfig([
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'precision' => 0,
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'formatter' => 'function(value) { return Math.round(value); }'
                    ]
                ]
            ]);

        foreach ($suratPerintahByInstance as $data) {
            $sptInstanceChart->addColumn($data['instance_name'], $data['total'], '#1e3a8a');
        }

        // ========================================
        // SPPD BY INSTANCE
        // ========================================
        $sppdByInstance = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->select('instance_id', DB::raw('COUNT(*) as total'))
            ->groupBy('instance_id')
            ->with('instance')
            ->get()
            ->map(function ($item) {
                return [
                    'instance_name' => $item->instance ? $item->instance->name : '-',
                    'total' => $item->total
                ];
            });

        $sppdByInstance = $sppdByInstance->sortByDesc('total')->take(10)->values();

        // Build Column Chart for SPPD by Instance
        $sppdInstanceChart = (new ColumnChartModel())
            ->setTitle('Distribusi SPPD')
            ->setAnimated(true)
            ->withOnColumnClickEventName('onColumnClick')
            ->setDataLabelsEnabled(true)
            ->setColumnWidth(50)
            ->setColors(['#6366f1'])
            ->setJsonConfig([
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'precision' => 0,
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'formatter' => 'function(value) { return Math.round(value); }'
                    ]
                ]
            ]);

        foreach ($sppdByInstance as $data) {
            $sppdInstanceChart->addColumn($data['instance_name'], $data['total'], '#6366f1');
        }

        // ========================================
        // TOP 5 OPD BY TRIP COUNT (CURRENT MONTH)
        // ========================================
        $top5OpdByCount = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', now()->year)
            ->whereMonth('publication_date', now()->month)
            ->select('instance_id', DB::raw('COUNT(*) as total'))
            ->groupBy('instance_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('instance')
            ->get()
            ->map(function ($item) {
                return [
                    'instance_name' => $item->instance ? $item->instance->name : 'BUPATI',
                    'total' => $item->total
                ];
            });

        // ========================================
        // TOP 5 OPD BY COST (DUMMY DATA FOR NOW)
        // ========================================
        $top5OpdByCost = collect([
            ['instance_name' => 'Dinas Pendidikan', 'total_cost' => 125000000],
            ['instance_name' => 'Dinas Kesehatan', 'total_cost' => 98500000],
            ['instance_name' => 'Dinas Pekerjaan Umum', 'total_cost' => 87300000],
            ['instance_name' => 'Dinas Perhubungan', 'total_cost' => 76200000],
            ['instance_name' => 'Dinas Sosial', 'total_cost' => 65800000],
        ]);

        // ========================================
        // DESTINATION BY PROVINCE
        // ========================================
        $destinationByProvince = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->where('status', 'approved')
            ->whereNotNull('province_id')
            ->select('province_id', DB::raw('COUNT(*) as total'))
            ->groupBy('province_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $province = \App\Models\Province::where('id', (string)$item->province_id)->first();
                return [
                    'province_name' => $province ? $province->name : 'Unknown',
                    'total' => $item->total
                ];
            });

        // Build Pie Chart for Destination by Province
        $provinceChart = (new PieChartModel())
            ->setTitle('Provinsi Tujuan')
            ->setAnimated(true)
            ->withOnSliceClickEvent('onSliceClick')
            ->setDataLabelsEnabled(true)
            ->setColors(['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#6366f1', '#ef4444', '#84cc16', '#f97316']);

        if ($destinationByProvince->count() > 0) {
            foreach ($destinationByProvince as $data) {
                $provinceChart->addSlice($data['province_name'], $data['total'], '#' . substr(md5($data['province_name']), 0, 6));
            }
        } else {
            // Add dummy data if no data available
            $provinceChart->addSlice('Belum ada data', 1, '#e5e7eb');
        }

        // ========================================
        // DESTINATION BY REGENCY
        // ========================================
        $destinationByRegency = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->where('status', 'approved')
            ->whereNotNull('regency_id')
            ->select('regency_id', DB::raw('COUNT(*) as total'))
            ->groupBy('regency_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $regency = \App\Models\Regency::where('id', (string)$item->regency_id)->first();
                return [
                    'regency_name' => $regency ? $regency->name : 'Unknown',
                    'total' => $item->total
                ];
            });

        // Build Pie Chart for Destination by Regency
        $regencyChart = (new PieChartModel())
            ->setTitle('Kabupaten/Kota Tujuan')
            ->setAnimated(true)
            ->withOnSliceClickEvent('onSliceClick')
            ->setDataLabelsEnabled(true)
            ->setColors(['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#6366f1', '#ef4444', '#84cc16', '#f97316']);

        if ($destinationByRegency->count() > 0) {
            foreach ($destinationByRegency as $data) {
                $regencyChart->addSlice($data['regency_name'], $data['total'], '#' . substr(md5($data['regency_name']), 0, 6));
            }
        } else {
            // Add dummy data if no data available
            $regencyChart->addSlice('Belum ada data', 1, '#e5e7eb');
        }

        // ========================================
        // CALCULATE RATES
        // ========================================
        $suratPerintahCompletionRate = $totalSuratPerintah > 0
            ? round(($approvedSuratPerintah / $totalSuratPerintah) * 100, 1)
            : 0;
        $suratPerintahRejetedRate = $totalSuratPerintah > 0
            ? round(($rejectedSuratPerintah / $totalSuratPerintah) * 100, 1)
            : 0;

        $sppdCompletionRate = $totalSppd > 0
            ? round(($approvedSppd / $totalSppd) * 100, 1)
            : 0;
        $sppdRejectedRate = $totalSppd > 0
            ? round((($rejectedSppd) / $totalSppd) * 100, 1)
            : 0;

        // ========================================
        // ACTIVE EMPLOYEES AND INSTANCES COUNT
        // ========================================
        $totalEmployees = Employee::count();
        $totalInstances = Instance::count();

        $totalAnggaran = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->whereYear('publication_date', $this->yearNow)
            ->sum('anggaran_rekening');

        return view('livewire.dashboard', [
            'stats' => [
                // Surat Perintah Tugas Stats
                'total_surat_perintah' => $totalSuratPerintah,
                'draft_surat_perintah' => $draftSuratPerintah,
                'sent_surat_perintah' => $sentSuratPerintah,
                'approved_surat_perintah' => $approvedSuratPerintah,
                'rejected_surat_perintah' => $rejectedSuratPerintah,
                'surat_perintah_completion_rate' => $suratPerintahCompletionRate,
                'surat_perintah_rejection_rate' => $suratPerintahRejetedRate,

                // SPPD Stats
                'total_sppd' => $totalSppd,
                'draft_sppd' => $draftSppd,
                'sent_sppd' => $sentSppd,
                'approved_sppd' => $approvedSppd,
                'rejected_sppd' => $rejectedSppd,
                'sppd_completion_rate' => $sppdCompletionRate,
                'sppd_rejection_rate' => $sppdRejectedRate,

                // Current Month & Active Trips
                'current_month_spt' => $currentMonthSpt,
                'current_month_sppd' => $currentMonthSppd,
                'active_trips' => $activeTrips,

                // General Stats
                'total_employees' => $totalEmployees,
                'total_instances' => $totalInstances,
            ],
            'monthlySptChart' => $monthlySptChart,
            'monthlySppdChart' => $monthlySppdChart,
            'monthlyTripsChart' => $monthlyTripsChart,
            'monthlyCostChart' => $monthlyCostChart,
            'sptInstanceChart' => $sptInstanceChart,
            'sppdInstanceChart' => $sppdInstanceChart,
            'provinceChart' => $provinceChart,
            'regencyChart' => $regencyChart,
            'top5OpdByCount' => $top5OpdByCount,
            'top5OpdByCost' => $top5OpdByCost,
            'totalAnggaran' => $totalAnggaran,
        ]);
    }
}
