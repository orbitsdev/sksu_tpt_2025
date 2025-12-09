<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PersonalInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@gmail.com',
                'data' => [
                    'first_name' => 'Maria',
                    'middle_name' => 'Garcia',
                    'last_name' => 'Rodriguez',
                    'suffix' => null,
                    'sex' => 'Female',
                    'birth_date' => '1985-03-20',
                    'contact_number' => '09171111111',
                ]
            ],
            [
                'email' => 'staff@gmail.com',
                'data' => [
                    'first_name' => 'Roberto',
                    'middle_name' => 'Cruz',
                    'last_name' => 'Mendoza',
                    'suffix' => null,
                    'sex' => 'Male',
                    'birth_date' => '1990-07-12',
                    'contact_number' => '09172222222',
                ]
            ],
            [
                'email' => 'cashier@gmail.com',
                'data' => [
                    'first_name' => 'Anna',
                    'middle_name' => 'Reyes',
                    'last_name' => 'Santos',
                    'suffix' => null,
                    'sex' => 'Female',
                    'birth_date' => '1992-11-08',
                    'contact_number' => '09173333333',
                ]
            ],
            [
                'email' => 'support@gmail.com',
                'data' => [
                    'first_name' => 'Pedro',
                    'middle_name' => 'Alvarez',
                    'last_name' => 'Ramos',
                    'suffix' => 'Jr.',
                    'sex' => 'Male',
                    'birth_date' => '1988-09-25',
                    'contact_number' => '09174444444',
                ]
            ],
            [
                'email' => 'applicant@gmail.com',
                'data' => [
                    'first_name' => 'Juan',
                    'middle_name' => 'Santos',
                    'last_name' => 'Dela Cruz',
                    'suffix' => null,
                    'sex' => 'Male',
                    'birth_date' => '2004-05-15',
                    'contact_number' => '09171234567',
                ]
            ],
        ];

        foreach ($users as $userData) {
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                $this->command->warn("⚠️ No user found with email {$userData['email']}. Skipping.");
                continue;
            }

            DB::table('personal_information')->updateOrInsert(
                ['user_id' => $user->id],
                array_merge($userData['data'], [
                    'email' => $user->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );

            $this->command->info("✅ Personal information seeded for {$userData['email']}");
        }
    }
}
