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
       // Create test accounts
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'campus_id' => 1
            ]
        );
        $admin->assignRole('admin');

        // $staff = User::firstOrCreate(
        //     ['email' => 'staff@gmail.com'],
        //     [
        //         'name' => 'Staff User',
        //         'password' => Hash::make('password'),
        //     ]
        // );
        // $staff->assignRole('staff');

        // $student = User::firstOrCreate(
        //     ['email' => 'student@gmail.com'],
        //     [
        //         'name' => 'Student User',
        //         'password' => Hash::make('password'),
        //     ]
        // );
        // $student->assignRole('student');
        $student = User::firstOrCreate(
            ['email' => 'applicant@gmail.com'],
            [
                'name' => 'Applicant User',
                'password' => Hash::make('password'),
                'campus_id' => 1
            ]
        );
        $student->assignRole('student');

        $this->command->info('✅ Default users created and assigned roles.');
    }
}
