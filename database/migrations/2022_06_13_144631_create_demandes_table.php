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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id')->references('id')->on('cars');
            $table->dateTime('date');
            $table->text('address');
            $table->text('comment')->nullable();
            $table->text('motif')->nullable();
            $table->text('etat')->default('new');
            $table->integer('user_id')->references('id')->on('users');
            $table->integer('admin_id')->references('id')->on('users')->nullable();
            $table->integer('mechanic_id')->references('id')->on('users')->nullable();
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
        Schema::dropIfExists('demandes');
    }
};
