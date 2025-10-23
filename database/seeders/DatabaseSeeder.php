<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\DefaultAccountSeeder;
use Database\Seeders\PersonalInformationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
    */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DefaultAccountSeeder::class,
            PersonalInformationSeeder::class
        ]);
    }
}
