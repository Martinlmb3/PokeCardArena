<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pokemaster extends Model
{
    protected $fillable = ['name', 'password', 'email', 'profile'];
    /** @use HasFactory<\Database\Factories\PokemasterFactory> */
    use HasFactory;
}
