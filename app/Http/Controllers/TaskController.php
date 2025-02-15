<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function create()
    {
        return view('createtask');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => 'Pending',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tasks.create')->with('success', 'Task created successfully!');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('edit-delete-task', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Task updated successfully!', 'redirect' => route('dashboard')]);
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->delete();
    
        $redirectTo = $request->input('redirect_to', 'dashboard');
    
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully!',
            'redirect' => $redirectTo === 'completed' ? route('taskcompleted') : route('dashboard')
        ]);
    }

    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->get();
        return view('tasks.index', compact('tasks'));
    }

    public function completed()
    {
        $completedTasks = Task::where('user_id', auth()->id())
                               ->where('status', 'Completed')
                               ->get();
        return view('taskcompleted', compact('completedTasks'));
    }
}