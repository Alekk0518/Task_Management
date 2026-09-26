<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Task::query();
        $filter = $request->input('filter', 'all');
        $search = $request->input('search');
        $today = now()->toDateString();

        $totalCount = Task::count();
        $completedCount = Task::where('status', 'Completed')->count();
        $pendingCount = Task::where('status', 'Pending')->count();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('task_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($filter === 'today') {
            $query->whereDate('due_date', $today);
        } elseif ($filter === 'upcoming') {
            $query->whereDate('due_date', '>', $today);
        } elseif ($filter === 'overdue') {
            $query->whereDate('due_date', '<', $today)->where('status', 'Pending');
        }

        $tasks = $query->orderBy('due_date')->get();

        return view('tasks.index', compact('tasks', 'filter', 'search', 'totalCount', 'completedCount', 'pendingCount'));
    }
    // Show the Add Task form
    public function create()
    {
        return view('tasks.create');
    }

    // Save a new task
    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'required|in:Pending,Completed',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'task_name' => $request->task_name,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        session()->flash('success', 'Task added successfully!');

        return response('', 302)
            ->header('Location', '/tasks');
    }

    // Show the Edit Task form
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // Update an existing task
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'task_name' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'required|in:Pending,Completed',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'task_name' => $request->task_name,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        session()->flash('success', 'Task updated successfully!');

        return response('', 302)
            ->header('Location', '/tasks');
    }

    // Soft delete a task (send to trash)
    public function destroy(Task $task)
    {
        $task->delete();

        session()->flash('success', 'Task moved to trash!');

        return response('', 302)
            ->header('Location', '/tasks');
    }

    // Change Pending o Completed
    public function updateStatus(Task $task)
    {
        if ($task->status === 'Pending') {
            $task->status = 'Completed';
        } else {
            $task->status = 'Pending';
        }

        $task->save();

        session()->flash('success', 'Task status updated!');

        return response('', 302)
            ->header('Location', '/tasks');
    }

    // --- TRASH BIN METHODS ---

    // Display soft-deleted tasks
    public function trash()
    {
        $tasks = Task::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('tasks.trash', compact('tasks'));
    }

    // Restore a soft-deleted task
    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $task->restore();

        session()->flash('success', 'Task restored successfully!');

        return response('', 302)
            ->header('Location', '/tasks/trash');
    }

    // Permanently delete a task
    public function forceDelete($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $task->forceDelete();

        session()->flash('success', 'Task permanently deleted!');

        return response('', 302)
            ->header('Location', '/tasks/trash');
    }
}