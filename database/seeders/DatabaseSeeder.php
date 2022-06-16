<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTable::class);
        $this->call(UserTable::class);
        $this->call(CarbirantTable::class);
        $this->call(OriginTable::class);
        $this->call(KilometerSeeder::class);

    }
}
