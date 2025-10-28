<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $examinations = [
            [
                'title' => 'College Entrance Examination',
                'start_date' => '2025-07-15',
                'end_date' => '2025-07-20',
                'venue' => 'SKSU Main Campus - Testing Center',
                // 'total_slots' => 500,
                'is_published' => true,
                'is_application_open' => false,
                'school_year' => '2025-2026',
                'type' => 'Entrance',
                'created_at' => $now,
                'updated_at' => $now,

            ],
            [
                'title' => 'Qualifying Examination for Transfer Students',
                'start_date' => '2025-08-10',
                'end_date' => '2025-08-12',
                'venue' => 'SKSU Isulan Campus - Academic Building B',
                //  'total_slots' => 200,
                'is_published' => true,
                'is_application_open' => true,
                'school_year' => '2025-2026',
                'type' => 'Qualifying',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Midterm Examination',
                'start_date' => '2025-10-05',
                'end_date' => '2025-10-10',
                'venue' => 'SKSU Access Campus - Computer Laboratory 2',
                //   'total_slots' => 300,
                'is_published' => false,
                'is_application_open' => false,
                'school_year' => '2025-2026',
                'type' => 'Midterm',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Final Examination',
                'start_date' => '2026-03-15',
                'end_date' => '2026-03-20',
                'venue' => 'SKSU Tacurong Campus - Examination Hall',
                //  'total_slots' => 350,
                'is_published' => false,
                'is_application_open' => false,
                'school_year' => '2025-2026',
                'type' => 'Final',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('examinations')->insert($examinations);

        $this->command->info('✅ ExaminationSeeder: ' . count($examinations) . ' examinations inserted successfully.');
    }
}
