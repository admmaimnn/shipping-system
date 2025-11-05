<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user')->get();
        $users = User::all();
        $totalTasks = $tasks->count();
        return view('tasks.index', compact('tasks', 'users', 'totalTasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'nullable|string|max:50',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'due_date', 'assigned_to', 'status']);
        $data['status'] = $data['status'] ?? 'Pending';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        Task::create($data);

        return redirect()->route('tasks.index')->with('success', '✅ Task created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
            'status' => 'required|string|max:50',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $task = Task::findOrFail($id);
        $data = $request->only(['title', 'description', 'due_date', 'assigned_to', 'status']);

        if ($request->hasFile('attachment')) {
            if ($task->attachment && Storage::disk('public')->exists($task->attachment)) {
                Storage::disk('public')->delete($task->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $task->update($data);

        return redirect()->route('tasks.index')->with('success', '✅ Task updated successfully!');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->attachment && Storage::disk('public')->exists($task->attachment)) {
            Storage::disk('public')->delete($task->attachment);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', '🗑️ Task deleted successfully!');
    }

    public function myTasks()
    {
        $tasks = Task::where('assigned_to', Auth::id())->get();
        $totalTasks = $tasks->count();
        return view('tasks.my', compact('tasks', 'totalTasks'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->save();

        return redirect()->back()->with('success', '✅ Task status updated!');
    }
}
