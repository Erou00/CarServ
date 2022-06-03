<?php

namespace Database\Seeders;

use App\Models\Carbirant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarbirantTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        //Carbirant::truncat();
        Carbirant::create(['type' => 'Hybride']);
        Carbirant::create(['type' => 'Diesel']);
        Carbirant::create(['type' => 'Essence']);
        Carbirant::create(['type' => 'Electrique']);
        Carbirant::create(['type' => 'LPG']);

    }
}
