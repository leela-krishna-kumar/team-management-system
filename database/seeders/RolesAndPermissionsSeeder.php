<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $member = Role::create(['name' => 'Member']);

        // Create permissions
        $permissions = [
            'create teams',
            'view teams',
            'delete teams',
            'update teams',
            'create tasks',
            'view tasks',
            'delete tasks',
            'update tasks',
            'assign tasks',
            'create roles',
            'view roles',
            'delete roles',
            'update roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo(['view teams', 'create tasks', 'view tasks', 'delete tasks', 'update tasks', 'assign tasks']);
        $member->givePermissionTo(['view tasks']);
    }
}
