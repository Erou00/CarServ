<?php

namespace Database\Seeders;

use App\Models\Cmodel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cmodel::create([
            'modelAbbr' =>'giulietta',
            'model'=>'Giulietta',
            'MarqueID' => 1
        ]);

        Cmodel::create([
            'modelAbbr' =>'gt',
            'model'=>'Gt',
            'MarqueID' => 1
        ]);

        Cmodel::create([
            'modelAbbr' =>'spider',
            'model'=>'Spider',
            'MarqueID' => 1
        ]);

        Cmodel::create([
            'modelAbbr' =>'stelvio',
            'model'=>'Stelvio',
            'MarqueID' =>1
        ]);



        Cmodel::create([
            'modelAbbr' =>'dbs',
            'model'=>'DBS',
            'MarqueID' =>2
        ]);

        Cmodel::create([
            'modelAbbr' =>'vanquish',
            'model'=>'Vanquish',
            'MarqueID' =>2
        ]);

        Cmodel::create([
            'modelAbbr' =>'vantage',
            'model'=>'Vantage',
            'MarqueID' => 2
        ]);


        Cmodel::create([
            'modelAbbr' =>'virage',
            'model'=>'Virage',
            'MarqueID' => 2
        ]);

        Cmodel::create([
            'modelAbbr' =>'a7',
            'model'=>'A7',
            'MarqueID' =>3
        ]);

        Cmodel::create([
            'modelAbbr' =>'a8',
            'model'=>'A8',
            'MarqueID' =>3
        ]);

        Cmodel::create([
            'modelAbbr' =>'allroad',
            'model'=>'Allroad',
            'MarqueID' => 3
        ]);
        Cmodel::create([
            'modelAbbr' =>'coupe',
            'model'=>'Coupe',
            'MarqueID' => 3
        ]);

        Cmodel::create([
            'modelAbbr' =>'q7',
            'model'=>'Q7',
            'MarqueID' => 3
        ]);
        Cmodel::create([
            'modelAbbr' =>'q8',
            'model'=>'Q8',
            'MarqueID' => 3
        ]);



    }
}
