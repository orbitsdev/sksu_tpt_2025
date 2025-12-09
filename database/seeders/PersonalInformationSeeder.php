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
          $user = User::where('email', 'applicant@gmail.com')->first();

        if (!$user) {
            $this->command->warn('⚠️ No user found with email applicant@gmail.com. Skipping personal info seeding.');
            return;
        }

        // Insert or update personal information for that user
        DB::table('personal_information')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'last_name' => 'Dela Cruz',
                'suffix' => null,
                'sex' => 'Male',
                'birth_date' => '2004-05-15',
                'birth_place' => 'Koronadal City',
                'civil_status' => 'Single',
                'nationality' => 'Filipino',
                'religion' => 'Catholic',
                'email' => $user->email,
                'contact_number' => '09171234567',
                'house_no' => '123',
                'street' => 'Rizal Street',
                'barangay' => 'Poblacion',
                'municipality' => 'Isulan',
                'province' => 'Sultan Kudarat',
                'region' => 'Region XII',
                'zip_code' => '9800',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('✅ Personal information seeded for applicant@gmail.com');
    }
}
