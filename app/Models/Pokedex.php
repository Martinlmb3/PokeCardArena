<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokedex extends Model
{
    /** @use HasFactory<\Database\Factories\PokedexFactory> */
    use HasFactory;

    protected $fillable = [
        'pokemaster_id',
        'pokemon_id',
        'nbPokemon',
        'nbPokemonMythic', 
        'nbPokemonLeg'
    ];

    protected $attributes = [
        'nbPokemon' => 0,
        'nbPokemonMythic' => 0,
        'nbPokemonLeg' => 0
    ];

    // Relationships
    public function pokemaster()
    {
        return $this->belongsTo(Pokemaster::class);
    }

    public function pokemon()
    {
        return $this->hasMany(Pokemon::class);
    }
}
