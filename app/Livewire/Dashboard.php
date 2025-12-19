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

#[Layout('components.layouts.app')]
#[Title('Dashboard - SPPD Admin')]
class Dashboard extends Component
{
    public function render()
    {
        // ========================================
        // SURAT PERINTAH TUGAS STATISTICS
        // ========================================
        $totalSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })->count();

        $draftSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })->where('status', 'draft')->count();

        $approvedSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })->where('status', 'approved')->count();

        $rejectedSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })->where('status', 'rejected')->count();

        // ========================================
        // SPPD STATISTICS
        // ========================================
        $totalSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })->count();

        $draftSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })->where('status', 'draft')->count();

        $approvedSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })->where('status', 'approved')->count();

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
            ->orderBy('month', 'desc')
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
            ->orderBy('month', 'desc')
            ->get();

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

        // ========================================
        // RECENT SURAT PERINTAH
        // ========================================
        // $recentSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
        //     $query->where('instance_id', auth()->user()->instance_id);
        // })
        //     ->with(['employeeGiver', 'instance', 'publicationEmployee', 'sppds'])
        //     ->latest()
        //     ->take(10)
        //     ->get();

        // ========================================
        // RECENT SPPD
        // ========================================
        // $recentSppd = SPPD::when(auth()->user()->instance_id, function ($query) {
        //     $query->where('instance_id', auth()->user()->instance_id);
        // })
        //     ->with(['employeeGiver', 'employeeExecutor', 'instance', 'suratPerintah'])
        //     ->latest()
        //     ->take(10)
        //     ->get();

        // ========================================
        // CALCULATE RATES
        // ========================================
        $suratPerintahCompletionRate = $totalSuratPerintah > 0
            ? round(($approvedSuratPerintah / $totalSuratPerintah) * 100, 1)
            : 0;

        $sppdCompletionRate = $totalSppd > 0
            ? round(($approvedSppd / $totalSppd) * 100, 1)
            : 0;

        // ========================================
        // ACTIVE EMPLOYEES AND INSTANCES COUNT
        // ========================================
        $totalEmployees = Employee::count();
        $totalInstances = Instance::count();

        return view('livewire.dashboard', [
            'stats' => [
                // Surat Perintah Tugas Stats
                'total_surat_perintah' => $totalSuratPerintah,
                'draft_surat_perintah' => $draftSuratPerintah,
                'approved_surat_perintah' => $approvedSuratPerintah,
                'rejected_surat_perintah' => $rejectedSuratPerintah,
                'surat_perintah_completion_rate' => $suratPerintahCompletionRate,

                // SPPD Stats
                'total_sppd' => $totalSppd,
                'draft_sppd' => $draftSppd,
                'approved_sppd' => $approvedSppd,
                'sppd_completion_rate' => $sppdCompletionRate,

                // General Stats
                'total_employees' => $totalEmployees,
                'total_instances' => $totalInstances,
            ],
            'monthlySuratPerintah' => $monthlySuratPerintah,
            'monthlySppd' => $monthlySppd,
            'suratPerintahByInstance' => $suratPerintahByInstance,
            'sppdByInstance' => $sppdByInstance,
            // 'recentSuratPerintah' => $recentSuratPerintah,
            // 'recentSppd' => $recentSppd,
        ]);
    }
}
