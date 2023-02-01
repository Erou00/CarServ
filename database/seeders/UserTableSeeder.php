<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::truncate();


        $admin = User::create([
            'utilisateur' => 'Master',
            'nom' => 'master',
            'prenom' => 'master',
            'email' => 'master@master.com',
            'password' => Hash::make('123456789'),
        ]);

         $admin->attachRole('master');


    }
}
