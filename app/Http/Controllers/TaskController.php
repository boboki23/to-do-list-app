<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->orderby('created_at', 'desc')->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date']
        ]);

        $priority = $request->has('priority') ? $request->priority : 'medium';

        $task = Auth::user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $priority,
            'due_date' => $validated['due_date'] ?? null,
            'status' => 'pending'
        ]);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task ' . $task->title . ' Berhasil Ditambahkan!!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
        ]);
        $task->update($validated);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task ' . $task->title . ' Berhasil Diperbarui!!!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'string', 'in:pending,in_progress,completed']
        ]);

        $task->update([
            'status' => $request->status
        ]);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task ' . $task->title . ' Berhasil Diperbarui!!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        Task::destroy($task->id);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task ' . $task->title . ' Berhasil Dihapus!!!');
    }
}
