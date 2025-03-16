<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pokemon;
use App\Models\Trainer;

class Pokedex extends Model
{
    /** @use HasFactory<\Database\Factories\PokedexFactory> */
    protected $fillable = ['trainer_id', 'create_at', 'update_at', 'nbPokemon', 'nbPokemonMythic', 'nbPokemonLeg','pokemon_id','xp'];
    use HasFactory;

    /**
     * Get the pokemon associated with the pokedex.
     */
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class);
    }

    /**
     * Get the trainer associated with the pokedex.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
