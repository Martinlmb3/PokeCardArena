<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First let's transfer any existing XP data from pokedexes to trainers
        if (Schema::hasColumn('pokedexes', 'xp')) {
            $pokedexes = DB::table('pokedexes')->get();
            foreach ($pokedexes as $pokedex) {
                if (isset($pokedex->trainer_id) && isset($pokedex->xp)) {
                    DB::table('trainers')
                        ->where('id', $pokedex->trainer_id)
                        ->update(['xp' => $pokedex->xp]);
                }
            }
        }
        
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->dropColumn('xp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokedexes', function (Blueprint $table) {
            $table->integer('xp')->default(0);
        });
        
        // If we need to roll back, copy XP data from trainers back to pokedexes
        $trainers = DB::table('trainers')->get();
        foreach ($trainers as $trainer) {
            if (isset($trainer->id) && isset($trainer->xp)) {
                DB::table('pokedexes')
                    ->where('trainer_id', $trainer->id)
                    ->update(['xp' => $trainer->xp]);
            }
        }
    }
};
