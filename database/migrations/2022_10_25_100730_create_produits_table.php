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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categorie_id');
            $table->unsignedBigInteger('sous_categorie_id');
            $table->unsignedBigInteger('marque_id');
            $table->unsignedBigInteger('devise_id');
            $table->unsignedBigInteger('unite_reglementaire_id');
            $table->string('designation');
            $table->float('prix_unitaire');
            $table->integer('stock_min');
            $table->boolean('active');
            $table->integer('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('groupe_id')->nullable();
            $table->foreign('categorie_id')->references('id')->on('categories');
            $table->foreign('sous_categorie_id')->references('id')->on('sous_categories');
            $table->foreign('marque_id')->references('id')->on('marques');
            $table->foreign('devise_id')->references('id')->on('devises');
            $table->foreign('unite_reglementaire_id')->references('id')->on('unite_reglementaires');
            $table->foreign('groupe_id')->references('id')->on('groupes');

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
        Schema::dropIfExists('produits');
    }
};
