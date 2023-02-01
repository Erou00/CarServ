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
        Schema::create('bl_details', function (Blueprint $table) {
            $table->id();

            $table->integer('qte');
            $table->integer('qte_livree');
            $table->integer('bl_id')->references('id')->on('bls');
            $table->integer('magasin_id')->references('id')->on('magasins');
            $table->integer('sous_magasin_id')->references('id')->on('magasins');
            $table->integer('produit_id')->references('id')->on('produits');
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
        Schema::dropIfExists('bl_details');
    }
};
