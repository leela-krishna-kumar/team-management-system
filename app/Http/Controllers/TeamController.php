<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Spatie\Permission\Models\Role;

class TeamController extends Controller
{
    public function index()
    {
        // dd(auth()->user()->getRoleNames()); // List of roles
        // dd(auth()->user()->getAllPermissions()); // List of permissions
        // dd(auth()->user()->can('manage teams')); // Check specific permission

        $this->authorize('view teams');

        if(auth()->user()->hasRole('admin')){
            $teams = Team::all();
        }else{

            //in future we can establish a realtion and only show teams, user is part of.
            $teams = Team::all();
        }


        $tasks = Task::all();
        $users = User::all();

        return view('teams.index', compact('teams', 'tasks', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:teams|max:255',
            'description' => 'nullable|max:500',
        ]);

        $team = Team::create($validated);

        return response()->json($team, 201);
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('update teams');

        $validated = $request->only(['name', 'description']);

        $team->update($validated);

        return response()->json(['success' => true, 'team' => $team], 201);

        // return redirect()->back();
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete teams');

        $team->delete();

        return response()->noContent();
    }
}
