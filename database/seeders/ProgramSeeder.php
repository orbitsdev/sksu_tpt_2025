<?php

namespace Database\Seeders;

use Carbon\Carbon;
use League\Csv\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 📂 Path to your CSV file
        $csvPath = database_path('seeders/data/programs.csv');

        // ✅ Read CSV using League\Csv
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // first row = headers

        $programs = [];

        foreach ($csv as $record) {
            $programs[] = [
                'campus_id'   => $record['campus_id'] ?? null,
                'name'        => $record['name'] ?? '',
                'abbreviation'=> $record['abbreviation'] ?? null,
                'code'        => $record['code'] ?? null,
                'is_offered'  => filter_var($record['is_offered'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        // 🧾 Insert all data
        DB::table('programs')->insert($programs);

        $this->command->info('Programs table seeded successfully.');
    }
}
