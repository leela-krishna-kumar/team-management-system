<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermissionsController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        $permissions = Permission::all();

        return view('roles-permissions.index', compact('users', 'roles', 'permissions'));
    }

    public function assignRole(Request $request)
    {

        // dd($request->all());

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->syncRoles($validated['roles']); // Automatically updates `model_has_roles`

        return redirect()->back();
    }

    public function assignPermission(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);

        $role = Role::where('name', $validated['role'])->first();
        $role->givePermissionTo($validated['permission']);

        return response()->json([
            'success' => true,
            'message' => 'Permission assigned successfully.',
            'role' => $role->name,
            'permissions' => $role->permissions->pluck('name'),
        ]);
    }

    public function revokePermission(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);

        $role = Role::where('name', $validated['role'])->first();
        $role->revokePermissionTo($validated['permission']);

        return response()->json([
            'success' => true,
            'message' => 'Permission revoked successfully.',
            'role' => $role->name,
            'permissions' => $role->permissions->pluck('name'),
        ]);
    }
}
