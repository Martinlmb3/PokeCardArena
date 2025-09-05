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
            $table->integer('xp')->default(0);
            $table->foreignId('trainer_id')->references('id')->on('trainers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pokemon_id')->nullable();
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
