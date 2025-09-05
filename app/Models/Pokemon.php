<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    /** @use HasFactory<\Database\Factories\PokemonFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'image', 
        'is_legendary',
        'is_mythical',
        'capture_at',
        'pokedex_id'
    ];

    // Relationships
    public function pokedex()
    {
        return $this->belongsTo(Pokedex::class);
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'pokemon_types');
    }
}
