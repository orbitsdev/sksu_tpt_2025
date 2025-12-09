<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use PHPUnit\Event\Telemetry\System;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::create([
            'name' => 'Examinee Number Starting Point',
            'value' => '500000'
        ]);
    }
}
