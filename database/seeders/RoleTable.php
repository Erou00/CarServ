<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Role::truncate();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'responsable']);
        Role::create(['name' => 'client']);
        Role::create(['name' => 'mecanicien']);
    }
}
