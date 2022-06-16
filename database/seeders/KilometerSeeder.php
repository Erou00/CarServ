<?php

namespace Database\Seeders;

use App\Models\Kilometer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KilometerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Kilometer::create(["kilometers" => "0 - 4 999"]);
        Kilometer::create(["kilometers" => "5 000 - 9 999"]);
        Kilometer::create(["kilometers" => "10 000 - 14 999"]);
        Kilometer::create(["kilometers" => "15 000 - 19 999" ]);
        Kilometer::create(["kilometers" => "20 000 - 24 999"]);
        Kilometer::create(["kilometers" => "25 000 - 29 999"]);
        Kilometer::create(["kilometers" => "30 000 - 34 999"]);
        Kilometer::create(["kilometers" => "35 000 - 39 999"]);
        Kilometer::create(["kilometers" => "40 000 - 44 999"]);
        Kilometer::create(["kilometers" => "45 000 - 49 999"]);
        Kilometer::create(["kilometers" => "50 000 - 54 999"]);
        Kilometer::create(["kilometers" => "55 000 - 59 999"]);
        Kilometer::create(["kilometers" => "60 000 - 64 999"]);
        Kilometer::create(["kilometers" => "65 000 - 69 999"]);

        Kilometer::create(["kilometers" => "70 000 - 74 999"]);
        Kilometer::create(["kilometers" => "75 000 - 79 999"]);
        Kilometer::create(["kilometers" => "80 000 - 84 999"]);

        Kilometer::create(["kilometers" => "85 000 - 89 999"]);
        Kilometer::create(["kilometers" => "90 000 - 94 999"]);

        Kilometer::create(["kilometers" => "95 000 - 99 999"]);
        Kilometer::create(["kilometers" => "100 000 - 109 999"]);
        Kilometer::create(["kilometers" => "110 000 - 119 999"]);
        Kilometer::create(["kilometers" => "120 000 - 129 999"]);
        Kilometer::create(["kilometers" => "130 000 - 139 999"]);
        Kilometer::create(["kilometers" => "140 000 - 149 999"]);
        Kilometer::create(["kilometers" => "150 000 - 159 999"]);
        Kilometer::create(["kilometers" => "160 000 - 169 999"]);
        Kilometer::create(["kilometers" => "170 000 - 179 999"]);
        Kilometer::create(["kilometers" => "180 000 - 189 999"]);
        Kilometer::create(["kilometers" => "190 000 - 199 999"]);
        Kilometer::create(["kilometers" => "200 000 - 249 999"]);
        Kilometer::create(["kilometers" => "250 000 - 299 999"]);
        Kilometer::create(["kilometers" => "300 000 - 349 999"]);
        Kilometer::create(["kilometers" => "350 000 - 399 999"]);
        Kilometer::create(["kilometers" => "400 000 - 449 999"]);
        Kilometer::create(["kilometers" => "450 000 - 499 999"]);
        Kilometer::create(["kilometers" => "Plus de 500 000"]);

    }
}
