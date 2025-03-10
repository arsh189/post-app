<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'manage users',
            'view posts',
            'edit posts',
            'delete posts',
            'create posts'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Ensure roles exist before assigning
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'staff']);

        $superAdminRole->syncPermissions(Permission::all());
        $adminRole->syncPermissions(Permission::all());
        $userRole->syncPermissions(['create posts', 'view posts']);

        User::factory(5)->create()->each(function ($user) use ($superAdminRole) {
            $user->assignRole($superAdminRole);
        });

        User::factory(10)->create()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
        });

        User::factory(15)->create()->each(function ($user) use ($userRole) {
            $user->assignRole($userRole);
        });

        // Create a specific Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Create a specific Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($adminRole);

        // Create a specific Staff user
        $staff = User::create([
            'name' => 'Regular Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
        ]);
        $staff->assignRole($userRole);
    }
}
