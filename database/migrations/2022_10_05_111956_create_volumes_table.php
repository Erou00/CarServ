<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVolumesTable extends Migration {

	public function up()
	{
		Schema::create('volumes', function(Blueprint $table) {
            $table->id();
			$table->timestamps();
			$table->string('nom', 100);
			$table->string('code');
		});
	}

	public function down()
	{
		Schema::drop('volumes');
	}
}
