<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use App\Models\Examination;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CampusSeeder;
use Database\Seeders\ProgramSeeder;
use Database\Seeders\TestCenterSeeder;
use Database\Seeders\ExaminationSeeder;
use Database\Seeders\SystemSettingSeeder;
use Database\Seeders\DefaultAccountSeeder;
use Database\Seeders\ExaminationTestSeeder;
use Database\Seeders\PersonalInformationSeeder;
use Database\Seeders\ApplicationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
    */
    public function run(): void
    {
        $this->call([
            SystemSettingSeeder::class,
            CampusSeeder::class,
            RoleSeeder::class,
            DefaultAccountSeeder::class,
            PersonalInformationSeeder::class,
            ProgramSeeder::class,
            ExaminationSeeder::class,
            TestCenterSeeder::class,
            ExaminationTestSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}
