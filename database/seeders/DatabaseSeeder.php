<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Asset;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Roles
        // $adminRole = Role::create(['name' => 'admin']);
        // $managerRole = Role::create(['name' => 'manager']);

        // // Permissions
        // $permissions = ['manage_users', 'manage_assets', 'view_reports'];
        // foreach ($permissions as $perm) {
        //     $p = Permission::create(['name' => $perm]);
        //     $adminRole->permissions()->attach($p->id);
        // }

        // // Users
        // $admin = User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('password'),
        //     'role_id' => $adminRole->id,
        // ]);

        // $manager = User::create([
        //     'name' => 'Manager User',
        //     'email' => 'manager@example.com',
        //     'password' => Hash::make('password'),
        //     'role_id' => $managerRole->id,
        // ]);

        // // Assets
        // Asset::create([
        //     'name' => 'Dell Keyboard',
        //     'type' => 'keyboard',
        //     'status' => 'in_use',
        //     'user_id' => $manager->id,
        // ]);

        // Asset::create([
        //     'name' => 'Logitech Mouse',
        //     'type' => 'mouse',
        //     'status' => 'available',
        //     'user_id' => null,
        // ]);
    }
}
