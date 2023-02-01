<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaysTable extends Migration {

	public function up()
	{
		Schema::create('pays', function(Blueprint $table) {
			$table->id();
			$table->string('nom', 30);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('pays');
	}
}
