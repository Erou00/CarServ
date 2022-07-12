<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration {

	public function up()
	{
		Schema::create('services', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 255);
			$table->boolean('home_service');
            $table->string('image');
            $table->integer('price');
            $table->text('description');

		});
	}

	public function down()
	{
		Schema::drop('services');
	}
}
