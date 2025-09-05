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
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->renameColumn('trainer_id', 'pokemaster_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->renameColumn('pokemaster_id', 'trainer_id');
        });
    }
};
