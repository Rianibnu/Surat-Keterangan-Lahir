<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions - sesuai dengan yang digunakan di routes/web.php
        $permissions = [
            // Dashboard
            'dashboard.view',
            
            // Birth Records
            'birth-records.view',
            'birth-records.create',
            'birth-records.edit',
            'birth-records.delete',
            'birth-records.export',
            
            // SKL
            'skl.view',
            'skl.create',
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
            
            // Activity Log
            'activity-log.view',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - Full access to everything
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - Manage users, doctors, view logs, full birth records
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'dashboard.view',
            'birth-records.view',
            'birth-records.create',
            'birth-records.edit',
            'birth-records.delete',
            'birth-records.export',
            'skl.view',
            'skl.create',
            'skl.download-pdf',
            'doctors.view',
            'doctors.create',
            'doctors.edit',
            'doctors.delete',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'activity-log.view',
        ]);

        // Bidan - Manage birth records and print SKL
        $bidan = Role::firstOrCreate(['name' => 'bidan']);
        $bidan->givePermissionTo([
            'dashboard.view',
            'birth-records.view',
            'birth-records.create',
            'birth-records.edit',
            'birth-records.export',
            'skl.view',
            'skl.create',
            'skl.download-pdf',
            'doctors.view',
        ]);

        $this->command->info('Roles and Permissions seeded successfully!');
        $this->command->table(
            ['Role', 'Permissions Count'],
            [
                ['super-admin', $superAdmin->permissions->count()],
                ['admin', $admin->permissions->count()],
                ['bidan', $bidan->permissions->count()],
            ]
        );
    }
}
