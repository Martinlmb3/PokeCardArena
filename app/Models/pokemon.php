<?php

namespace App\Models;

use App\Models\Type;
use App\Models\Pokedex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    /** @use HasFactory<\Database\Factories\PokemonFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pokemon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'image',
        'is_legendary',
        'is_mythical',
        'capture_at',
        'pokedex_id',
    ];

    /**
     * Get the types associated with this pokemon.
     */
    public function types()
    {
        return $this->belongsToMany(Type::class, 'pokemon_types');
    }

    /**
     * Get the pokedex that owns the pokemon.
     */
    public function pokedex()
    {
        return $this->belongsTo(Pokedex::class);
    }
}
