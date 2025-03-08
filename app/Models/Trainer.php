<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Trainer extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'profile', 'xp', 'title'];
    /** @use HasFactory<\Database\Factories\PokemasterFactory> */
    use HasFactory;
}
