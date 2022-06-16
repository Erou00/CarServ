<?php

namespace Database\Seeders;

use App\Models\Origin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OriginTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Origin::create(['origin' => 'Imported new']);
        Origin::create(['origin' => 'WW at MOROCCO']);
        Origin::create(['origin' => 'Not cleared yet']);

    }
}
