<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $technician = auth()->user();

        // Görev istatistikleri
        $activeTasks = Task::where('technician_id', $technician->id)
            ->where('status', 'active')
            ->count();

        $pendingTasks = Task::where('technician_id', $technician->id)
            ->where('status', 'pending')
            ->count();

        $totalTasks = Task::where('technician_id', $technician->id)->count();

        $completedThisMonth = Task::where('technician_id', $technician->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', Carbon::now()->month)
            ->count();

        // Aylık performans grafiği
        $monthlyLabels = [];
        $monthlyData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            $monthlyData[] = Task::where('technician_id', $technician->id)
                ->where('status', 'completed')
                ->whereMonth('completed_at', $date->month)
                ->whereYear('completed_at', $date->year)
                ->count();
        }

        // Görev durumları
        $taskStatusLabels = ['Beklemede', 'Aktif', 'Tamamlandı', 'İptal'];
        $taskStatusData = [
            Task::where('technician_id', $technician->id)->where('status', 'pending')->count(),
            Task::where('technician_id', $technician->id)->where('status', 'active')->count(),
            Task::where('technician_id', $technician->id)->where('status', 'completed')->count(),
            Task::where('technician_id', $technician->id)->where('status', 'cancelled')->count()
        ];

        // Son görevler
        $recentTasks = Task::with('customer')
            ->where('technician_id', $technician->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($task) {
                return (object)[
                    'id' => $task->id,
                    'customer_name' => $task->customer->name,
                    'status' => $task->status,
                    'status_color' => $this->getStatusColor($task->status),
                    'created_at' => $task->created_at
                ];
            });

        return view('technician.dashboard', compact(
            'activeTasks',
            'pendingTasks',
            'totalTasks',
            'completedThisMonth',
            'monthlyLabels',
            'monthlyData',
            'taskStatusLabels',
            'taskStatusData',
            'recentTasks'
        ));
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'warning',
            'active' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
} 