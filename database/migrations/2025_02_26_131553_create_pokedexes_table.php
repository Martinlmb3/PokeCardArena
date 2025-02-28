<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pokedexes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('nbPokemon');
            $table->integer('nbPokemonMythic');
            $table->integer('nbPokemonLeg');
            $table->integer('idPokemaster')->unique();
            $table->integer('idPokemon')->unique();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokedexes');
    }
};
