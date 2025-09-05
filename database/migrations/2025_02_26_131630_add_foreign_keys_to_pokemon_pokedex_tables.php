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
        Schema::table('pokemon', function (Blueprint $table) {
            $table->foreign('pokedex_id')->references('id')->on('pokedexes')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('pokedexes', function (Blueprint $table) {
            $table->foreign('pokemon_id')->references('id')->on('pokemon')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemon', function (Blueprint $table) {
            $table->dropForeign(['pokedex_id']);
        });

        Schema::table('pokedexes', function (Blueprint $table) {
            $table->dropForeign(['pokemon_id']);
        });
    }
};