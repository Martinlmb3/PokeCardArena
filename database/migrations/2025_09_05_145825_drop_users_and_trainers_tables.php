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
        // Check if columns exist before trying to manipulate them
        $hasTrainerIdColumn = Schema::hasColumn('pokedexes', 'trainer_id');
        $hasPokemasterId = Schema::hasColumn('pokedexes', 'pokemaster_id');
        
        if ($hasTrainerIdColumn && !$hasPokemasterId) {
            // Only proceed if we need to migrate from trainer_id to pokemaster_id
            
            // First drop foreign keys that might exist
            Schema::table('pokedexes', function (Blueprint $table) {
                // Try to drop foreign keys if they exist
                try {
                    $table->dropForeign(['trainer_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                try {
                    $table->dropForeign(['pokemon_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
            });
            
            if (Schema::hasTable('pokemon')) {
                Schema::table('pokemon', function (Blueprint $table) {
                    try {
                        $table->dropForeign(['pokedex_id']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, continue
                    }
                });
            }
            
            // Now safely update the pokedexes table structure
            Schema::table('pokedexes', function (Blueprint $table) {
                $table->dropColumn('trainer_id');
                $table->foreignId('pokemaster_id')->nullable()->constrained('pokemasters')->onDelete('cascade');
            });
            
            // Re-add the foreign keys we need to keep
            Schema::table('pokedexes', function (Blueprint $table) {
                if (Schema::hasTable('pokemon')) {
                    $table->foreign('pokemon_id')->references('id')->on('pokemon')->onUpdate('cascade')->onDelete('cascade');
                }
            });
            
            if (Schema::hasTable('pokemon')) {
                Schema::table('pokemon', function (Blueprint $table) {
                    $table->foreign('pokedex_id')->references('id')->on('pokedexes')->onUpdate('cascade')->onDelete('cascade');
                });
            }
        } elseif (!$hasPokemasterId) {
            // If no trainer_id column exists, just add pokemaster_id
            Schema::table('pokedexes', function (Blueprint $table) {
                $table->foreignId('pokemaster_id')->nullable()->constrained('pokemasters')->onDelete('cascade');
            });
        }
        
        // Drop the tables if they exist
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
