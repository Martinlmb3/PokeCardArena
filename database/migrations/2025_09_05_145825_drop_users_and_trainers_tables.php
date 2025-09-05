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
        // First drop all foreign keys that depend on trainers table
        Schema::table('pokedexes', function (Blueprint $table) {
            // Drop foreign keys to avoid dependency issues
            $table->dropForeign(['trainer_id']);
            $table->dropForeign(['pokemon_id']);
        });
        
        Schema::table('pokemon', function (Blueprint $table) {
            $table->dropForeign(['pokedex_id']);
        });
        
        // Now safely update the pokedexes table structure
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->dropColumn('trainer_id');
            $table->foreignId('pokemaster_id')->nullable()->constrained('pokemasters')->onDelete('cascade');
        });
        
        // Re-add the foreign keys we need to keep
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->foreign('pokemon_id')->references('id')->on('pokemon')->onUpdate('cascade')->onDelete('cascade');
        });
        
        Schema::table('pokemon', function (Blueprint $table) {
            $table->foreign('pokedex_id')->references('id')->on('pokedexes')->onUpdate('cascade')->onDelete('cascade');
        });
        
        // Then drop the tables
        Schema::dropIfExists('trainers');
        Schema::dropIfExists('users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate tables if needed (simplified versions)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
        
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('title')->default('Rookie Trainer');
            $table->integer('xp')->default(0);
            $table->timestamps();
        });
        
        // Restore the original foreign key structure
        Schema::table('pokedexes', function (Blueprint $table) {
            // Drop current foreign keys
            $table->dropForeign(['pokemon_id']);
        });
        
        Schema::table('pokemon', function (Blueprint $table) {
            $table->dropForeign(['pokedex_id']);
        });
        
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->dropForeign(['pokemaster_id']);
            $table->dropColumn('pokemaster_id');
            $table->foreignId('trainer_id')->nullable()->constrained('trainers')->onDelete('cascade');
        });
        
        // Restore the circular foreign keys
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->foreign('pokemon_id')->references('id')->on('pokemon')->onUpdate('cascade')->onDelete('cascade');
        });
        
        Schema::table('pokemon', function (Blueprint $table) {
            $table->foreign('pokedex_id')->references('id')->on('pokedexes')->onUpdate('cascade')->onDelete('cascade');
        });
    }
};
