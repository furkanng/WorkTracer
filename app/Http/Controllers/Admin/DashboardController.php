<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Task;
use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Müşteri istatistikleri
        $totalCustomers = Customer::count();
        $newCustomers = Customer::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Görev istatistikleri
        $activeTasks = Task::where('status', 'active')->count();
        $pendingTasks = Task::where('status', 'pending')->count();

        // Teknisyen istatistikleri
        $totalTechnicians = User::where('role_id', 2)->count();
        $activeTechnicians = User::where('role_id', 2)->count();

        // Aylık görev istatistikleri
        $monthlyTaskLabels = [];
        $monthlyTaskData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyTaskLabels[] = $date->format('M Y');
            $monthlyTaskData[] = Task::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', 'completed')
                ->count();
        }

        // Görev durumları
        $taskStatusLabels = ['Beklemede', 'Aktif', 'Tamamlandı', 'İptal'];
        $taskStatusData = [
            Task::where('status', 'pending')->count(),
            Task::where('status', 'active')->count(),
            Task::where('status', 'completed')->count(),
            Task::where('status', 'cancelled')->count()
        ];

        // Son görevler
        $recentTasks = Task::with('customer')
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

        // Son mesajlar
        $recentMessages = Message::with('sender')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($message) {
                return (object)[
                    'sender_name' => $message->sender->name,
                    'subject' => $message->subject,
                    'created_at' => $message->created_at
                ];
            });

        return view('admin.dashboard', compact(
            'totalCustomers',
            'newCustomers',
            'activeTasks',
            'pendingTasks',
            'totalTechnicians',
            'activeTechnicians',
            'monthlyTaskLabels',
            'monthlyTaskData',
            'taskStatusLabels',
            'taskStatusData',
            'recentTasks',
            'recentMessages'
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