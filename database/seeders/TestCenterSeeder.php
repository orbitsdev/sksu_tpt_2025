<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Create independent test centers (not tied to specific examinations)
        $testCenters = [
            [
                'campus_id' => 1, // ACCESS Campus
                'name' => 'ACCESS Campus - Testing Center',
                'address' => 'Sultan Kudarat State University, ACCESS Campus, EJC Montilla, Tacurong City',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'campus_id' => 2, // Isulan Campus
                'name' => 'Isulan Campus - Academic Building B',
                'address' => 'Sultan Kudarat State University, Isulan Campus, Isulan, Sultan Kudarat',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'campus_id' => 3, // Tacurong Campus
                'name' => 'Tacurong Campus - Computer Laboratory',
                'address' => 'Sultan Kudarat State University, Tacurong Campus, Tacurong City, Sultan Kudarat',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('test_centers')->insert($testCenters);

        $this->command->info('✅ TestCenterSeeder: ' . count($testCenters) . ' test centers inserted successfully.');
    }
}
