<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // $this->call(RoleTableSeeder::class);
        // $this->call(ServiceTableSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(UserTableSeeder::class);

    }
}
