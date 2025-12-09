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
                'is_public' => true,
                'application_open' => false,
                'school_year' => '2025-2026',
                'exam_type' => 'Entrance',
                'is_results_published' => false,
                'application_start_date' => '2025-06-01',
                'application_end_date' => '2025-07-10',
                'created_at' => $now,
                'updated_at' => $now,

            ],
            [
                'title' => 'Qualifying Examination for Transfer Students',
                'start_date' => '2025-08-10',
                'end_date' => '2025-08-12',
                'is_public' => true,
                'application_open' => true,
                'school_year' => '2025-2026',
                'exam_type' => 'Qualifying',
                'is_results_published' => false,
                'application_start_date' => '2025-07-01',
                'application_end_date' => '2025-08-05',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Midterm Examination',
                'start_date' => '2025-10-05',
                'end_date' => '2025-10-10',
                'is_public' => false,
                'application_open' => false,
                'school_year' => '2025-2026',
                'exam_type' => 'Midterm',
                'is_results_published' => false,
                'application_start_date' => '2025-09-01',
                'application_end_date' => '2025-10-01',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Final Examination',
                'start_date' => '2026-03-15',
                'end_date' => '2026-03-20',
                'is_public' => false,
                'application_open' => false,
                'school_year' => '2025-2026',
                'exam_type' => 'Final',
                'is_results_published' => false,
                'application_start_date' => '2026-02-01',
                'application_end_date' => '2026-03-10',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('examinations')->insert($examinations);

        $this->command->info('✅ ExaminationSeeder: ' . count($examinations) . ' examinations inserted successfully.');
    }
}
