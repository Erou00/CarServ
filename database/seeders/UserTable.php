<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTable extends Seeder
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

        $adminRole = Role::where('name','admin')->first();

        $admin = User::create([
            'last_name' => 'admin',
            'first_name' => 'admin',
            'cin' => 'cin',
            'email' => 'admin@admin.com',
            'adress' => 'adress',
            'phone_number' => '0293892731',
            'image'=>'default.png',
            'password' => Hash::make('123456789'),
        ]);

        $admin->roles()->attach($adminRole);

        $clientRole = Role::where('name','client')->first();
        $client = User::create([
            'last_name' => 'ayoub',
            'first_name' => 'essahat',
            'cin' => 'AD875412',
            'email' => 'ayoub_esst@live.fr',
            'adress' => 'adress',
            'phone_number' => '0689451278',
            'image'=>'default.png',
            'password' => Hash::make('123456789'),
        ]);

        $client->roles()->attach($clientRole);
    }
}
