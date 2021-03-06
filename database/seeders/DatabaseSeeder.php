<?php

namespace Database\Seeders;

use App\Models\Product;
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
        $this->call(MarkTableSeeder::class);
        $this->call(ModelTableSeeder::class);
        Product::factory(25)->create();

    }
}
