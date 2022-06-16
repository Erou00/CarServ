<?php

namespace Database\Seeders;

use App\Models\Mark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Mark::create([
            'name' => 'Alfa Romeo',
            'logo' => 'Alfa-Romeo.png'
        ]);

        Mark::create([
            'name' => 'Aston Martin',
            'logo' => 'Aston-Martin.png'
        ]);

        Mark::create([
            'name' => 'Audi',
            'logo' =>'Audi.png'
        ]);


    }
}
