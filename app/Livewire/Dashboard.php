<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Sppd;
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
        // Basic statistics
        $totalSuratPerintah = SuratPerintah::count();
        $pendingSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->where('status', 'pending')->count();
        $approvedSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->where('status', 'approved')->count();
        $rejectedSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->where('status', 'rejected')->count();

        $totalSppd = Sppd::count();
        // $pendingSppd = Sppd::when(auth()->user()->instance_id, function ($query) {
        //     $query->where('instance_id', auth()->user()->instance_id);
        // })
        //     ->where('status', 'pending')->count();
        // $approvedSppd = Sppd::when(auth()->user()->instance_id, function ($query) {
        //     $query->where('instance_id', auth()->user()->instance_id);
        // })
        //     ->where('status', 'approved')->count();
        // $rejectedSppd = Sppd::when(auth()->user()->instance_id, function ($query) {
        //     $query->where('instance_id', auth()->user()->instance_id);
        // })
        //     ->where('status', 'rejected')->count();
        // $completedSppd = Sppd::when(auth()->user()->instance_id, function ($query) {
        //     $query->where('instance_id', auth()->user()->instance_id);
        // })
        //     ->where('status', 'completed')->count();

        // Monthly statistics (last 6 months)
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

        // SPPD by instance
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

        $suratPerintahByInstance = $suratPerintahByInstance->sortByDesc('total')->values();

        // Recent SPPD with relationships
        $recentSppd = Sppd::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->with(['employeeGiver', 'employeeExecutor', 'instance'])
            ->latest()
            ->take(10)
            ->get();

        $recentSuratPerintah = SuratPerintah::when(auth()->user()->instance_id, function ($query) {
            $query->where('instance_id', auth()->user()->instance_id);
        })
            ->with(['employeeGiver', 'instance'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate completion rate
        // $completionRate = $totalSuratPerintah > 0 ? round(($completedSppd / $totalSuratPerintah) * 100, 1) : 0;
        $approvalRate = $totalSuratPerintah > 0 ? round(($approvedSuratPerintah / $totalSuratPerintah) * 100, 1) : 0;

        // Active employees and instances count
        $totalEmployees = Employee::count();
        $totalInstances = Instance::count();

        return view('livewire.dashboard', [
            'stats' => [
                'total_sppd' => $totalSppd,
                'total_surat_perintah' => $totalSuratPerintah,
                'pending_surat_perintah' => $pendingSuratPerintah,
                'approved_surat_perintah' => $approvedSuratPerintah,
                'rejected_surat_perintah' => $rejectedSuratPerintah,
                // 'pending_sppd' => $pendingSppd,
                // 'approved_sppd' => $approvedSppd,
                // 'rejected_sppd' => $rejectedSppd,
                // 'completed_sppd' => $completedSppd,
                'total_employees' => $totalEmployees,
                'total_instances' => $totalInstances,
                // 'completion_rate' => $completionRate,
                'approval_rate' => $approvalRate,
            ],
            'monthlySuratPerintah' => $monthlySuratPerintah,
            'suratPerintahByInstance' => $suratPerintahByInstance,
            'recentSuratPerintah' => $recentSuratPerintah,
            'recentSppd' => $recentSppd,
        ]);
    }
}
