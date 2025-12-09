<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'campus_id' => 1
            ]
        );
        $admin->assignRole('admin');

        // Staff User
        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'campus_id' => 1
            ]
        );
        $staff->assignRole('staff');

        // Cashier User
        $cashier = User::firstOrCreate(
            ['email' => 'cashier@gmail.com'],
            [
                'name' => 'Cashier User',
                'password' => Hash::make('password'),
                'campus_id' => 1
            ]
        );
        $cashier->assignRole('cashier');

        // Support User (handles tickets/concerns)
        $support = User::firstOrCreate(
            ['email' => 'support@gmail.com'],
            [
                'name' => 'Support User',
                'password' => Hash::make('password'),
                'campus_id' => 1
            ]
        );
        $support->assignRole('support');

        // Student/Applicant User
        $student = User::firstOrCreate(
            ['email' => 'applicant@gmail.com'],
            [
                'name' => 'Applicant User',
                'password' => Hash::make('password'),
                'campus_id' => 1
            ]
        );
        $student->assignRole('student');

        $this->command->info('✅ Default users created: admin, staff, cashier, support, applicant');
    }
}
