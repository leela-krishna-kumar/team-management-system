<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function assignTask(Request $request, Task $task)
    {
        // dd($request->all());

        $this->authorize('assign tasks');

        // $validated = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        // ]);

        $task->users()->attach($request->user_ids, ['assigned_at' => now()]);

        // $task->users()->sync();

        return redirect()->back()->with('message', 'Task assigned successfully.');
    }
}

