<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate the necessary tables
        $this->truncateTables([
            'users',
            'model_has_roles', // Assuming you're using Spatie's roles table
            'roles',
            'permissions', // Truncate permissions table if needed
        ]);

        // Run the RolesAndPermissionsSeeder first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create users and assign roles
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin1234'), // Admin password
        ]);
        $admin->assignRole('Admin');

        $manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager1234'), // Manager password
        ]);
        $manager->assignRole('Manager');

        $member = User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => Hash::make('member1234'), // Member password
        ]);
        $member->assignRole('Member');
    }

    /**
     * Truncate the given tables.
     *
     * @param array $tables
     * @return void
     */
    private function truncateTables(array $tables): void
    {
        // Disable foreign key checks to truncate tables that have foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate each table
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Enable foreign key checks again
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
