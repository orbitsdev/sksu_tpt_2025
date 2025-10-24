<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $campuses = [
            ['name' => 'ACCESS Campus', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Isulan Campus', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tacurong Campus', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kalamansig Campus', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bagumbayan Campus', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Palimbang Campus', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lutayan Campus', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Senator Ninoy Aquino National High School - Kulaman, Sultan Kudarat', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('campuses')->insert($campuses);
    }
}
