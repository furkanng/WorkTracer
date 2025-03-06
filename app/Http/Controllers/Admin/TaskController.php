<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Mail\NewTaskAssigned;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['customer', 'technician', 'taskType'])
            ->latest()
            ->paginate(10);
        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $taskTypes = TaskType::all();
        $technicians = User::where('role_id', 2)->get(); // role_id 2 teknisyenler için
        $customers = Customer::all();
        return view('admin.tasks.create', compact('taskTypes', 'technicians', 'customers'));
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

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Görev başarıyla oluşturuldu ve teknisyene bildirildi.');
    }

    public function show(Task $task)
    {
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $taskTypes = TaskType::all();
        $technicians = User::where('role_id', 2)->get();
        $customers = Customer::all();
        return view('admin.tasks.edit', compact('task', 'taskTypes', 'technicians', 'customers'));
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

        $task->update($validated);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Görev başarıyla güncellendi.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')
            ->with('success', 'Görev başarıyla silindi.');
    }
} 