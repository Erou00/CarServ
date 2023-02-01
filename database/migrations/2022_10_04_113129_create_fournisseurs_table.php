<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ville_id')->nullable();
			$table->string('nom', 60);
			$table->string('representant', 60)->nullable();
			$table->string('adresse', 250)->nullable();
			$table->string('telephone', 100)->nullable();
			$table->string('fax', 20)->nullable();
			$table->string('email', 30)->nullable();
			$table->string('siteweb', 30)->nullable();
			$table->string('patente', 30)->nullable();
            $table->foreign('ville_id')->references('id')->on('villes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fournisseurs');
    }
};
