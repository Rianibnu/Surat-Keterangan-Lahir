<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Birth Records
            'birth-records.view',
            'birth-records.create',
            'birth-records.edit',
            'birth-records.delete',
            'birth-records.export',

            // SKL
            'skl.view',
            'skl.create',
            'skl.print',
            'skl.download-pdf',

            // Doctors
            'doctors.view',
            'doctors.create',
            'doctors.edit',
            'doctors.delete',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles & Permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Activity Log
            'activity-log.view',

            // Dashboard
            'dashboard.view',
            'dashboard.analytics',

            // Settings
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Admin - has all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Staf Input - can manage birth records and SKL
        $stafRole = Role::firstOrCreate(['name' => 'staf']);
        $stafRole->syncPermissions([
            'birth-records.view',
            'birth-records.create',
            'birth-records.edit',
            'skl.view',
            'skl.create',
            'skl.print',
            'skl.download-pdf',
            'doctors.view',
            'dashboard.view',
        ]);

        // Bidan - can view and print
        $bidanRole = Role::firstOrCreate(['name' => 'bidan']);
        $bidanRole->syncPermissions([
            'birth-records.view',
            'skl.view',
            'skl.print',
            'skl.download-pdf',
            'doctors.view',
            'dashboard.view',
        ]);

        // Viewer - read only
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerRole->syncPermissions([
            'birth-records.view',
            'skl.view',
            'doctors.view',
            'dashboard.view',
        ]);

        // Assign admin role to first user if exists
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('admin')) {
            $firstUser->assignRole('admin');
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
