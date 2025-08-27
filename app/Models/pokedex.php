<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pokedex extends Model
{
    /** @use HasFactory<\Database\Factories\PokedexFactory> */
    use HasFactory;

    protected $fillable = [
        'trainer_id',
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
}
