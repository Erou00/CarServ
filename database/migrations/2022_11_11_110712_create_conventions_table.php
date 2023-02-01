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
        Schema::create('conventions', function (Blueprint $table) {
            $table->id();
            $table->string('no_convention');
            $table->string('ligne_budgetaire')->nullable();
            $table->string('objet')->nullable();
            $table->integer('delais_execution');
            $table->date('ods');
            $table->integer('fournisseur_id')->references('id')->on('fournisseurs');
            $table->integer('tva')->default(0);
            $table->integer('magasin_id')->references('id')->on('magasins');
            $table->integer('sous_magasin_id')->references('id')->on('magasins');
            $table->boolean('imp')->default(false);
            $table->integer('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('conventions');
    }
};
