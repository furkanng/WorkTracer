<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\User;
use App\Models\Customer;
use App\Mail\NewTaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['customer', 'technician', 'taskType'])
            ->latest()
            ->paginate(10);
            
        return view('secretary.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $taskTypes = TaskType::all();
        $technicians = User::where('role_id', 2)->get(); // role_id 2 teknisyenler için
        
        return view('secretary.tasks.create', compact('customers', 'taskTypes', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'technician_id' => 'required|exists:users,id',
            'task_type_id' => 'required|exists:task_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'brand_id' => 'nullable|exists:brands,id'
        ]);

        $task = Task::create($validated);

        // Teknisyene mail gönder
        try {
            Mail::to($task->technician->email)->send(new NewTaskAssigned($task));
        } catch (\Exception $e) {
            // Mail gönderilemese bile işleme devam et
            report($e);
        }

        return redirect()->route('secretary.tasks.index')
            ->with('success', 'Görev başarıyla oluşturuldu ve teknisyene bildirildi.');
    }

    public function show(Task $task)
    {
        return view('secretary.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $customers = Customer::orderBy('name')->get();
        $taskTypes = TaskType::all();
        $technicians = User::where('role_id', 2)->get();
        
        return view('secretary.tasks.edit', compact('task', 'customers', 'taskTypes', 'technicians'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'technician_id' => 'required|exists:users,id',
            'task_type_id' => 'required|exists:task_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'brand_id' => 'nullable|exists:brands,id'
        ]);

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $oldTechnicianId = $task->technician_id;
        $task->update($validated);

        // Eğer teknisyen değiştiyse yeni teknisyene mail gönder
        if ($oldTechnicianId !== $task->technician_id) {
            try {
                Mail::to($task->technician->email)->send(new NewTaskAssigned($task));
            } catch (\Exception $e) {
                report($e);
            }
        }

        return redirect()->route('secretary.tasks.index')
            ->with('success', 'Görev başarıyla güncellendi.');
    }
} 