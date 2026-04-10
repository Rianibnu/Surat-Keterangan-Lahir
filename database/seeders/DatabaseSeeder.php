<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');
        $this->command->newLine();

        // 1. Roles and Permissions (harus pertama)
        $this->command->info('📋 Seeding Roles & Permissions...');
        $this->call(RolePermissionSeeder::class);
        $this->command->newLine();

        // 2. Users with roles
        $this->command->info('👥 Seeding Users...');
        $this->call(UserSeeder::class);
        $this->command->newLine();

        // 3. Doctors
        $this->command->info('👨‍⚕️ Seeding Doctors...');
        $this->call(DoctorSeeder::class);
        $this->command->newLine();

        // 4. Birth Records
        $this->command->info('👶 Seeding Birth Records...');
        $this->call(BirthRecordSeeder::class);
        $this->command->newLine();

        // 5. SKL Documents
        $this->command->info('📄 Seeding SKL Documents...');
        $this->call(SklSeeder::class);
        $this->command->newLine();

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();

        // Summary
        $this->command->info('📊 Summary:');
        $this->command->table(
            ['Model', 'Count'],
            [
                ['Users', \App\Models\User::count()],
                ['Doctors', \App\Models\Doctor::count()],
                ['Birth Records', \App\Models\BirthRecord::count()],
                ['SKL Documents', \App\Models\Skl::count()],
                ['Roles', \Spatie\Permission\Models\Role::count()],
                ['Permissions', \Spatie\Permission\Models\Permission::count()],
            ]
        );

        $this->command->newLine();
        $this->command->info('🔐 Login Credentials:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin', 'superadmin@rsukm.com', 'password'],
                ['Super Admin', 'test@example.com', 'password'],
                ['Admin', 'admin@rsukm.com', 'password'],
                ['Bidan', 'bidan.siti@rsukm.com', 'password'],
                ['Bidan', 'bidan.dewi@rsukm.com', 'password'],
                ['Bidan', 'bidan.ratna@rsukm.com', 'password'],
            ]
        );
    }
}
