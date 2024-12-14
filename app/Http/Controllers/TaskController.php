<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Team $team)
    {
        // $this->authorize('view', $team);

        $tasks = $team->tasks()->with('users')->get();
        $users = User::all();

        return view('tasks.index', compact('team', 'tasks', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'title' => 'required|max:255',
            'description' => 'nullable|max:500',
            'status' => 'required|in:pending,completed',
            'due_date' => 'required|date',
        ]);

        $task = Task::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'field' => 'required|string|in:title,description,status,due_date',
            'value' => 'required',
        ]);

        $task->update([$validated['field'] => $validated['value']]);

        return response()->json(['success' => true, 'task' => $task], 201);
    }

    public function assignUsers(Request $request, Task $task)
    {
        $validated = $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $task->users()->sync($validated['users']);

        $updatedUsers = $task->users()->get();

        return response()->json(['success' => true, 'users' => $updatedUsers], 201);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(['success' => true], 201);
    }
}
