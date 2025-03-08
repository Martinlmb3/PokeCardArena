<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        Trainer::create([
            'name' => 'Test Trainer',
            'email' => 'trainer@pokemon.com',
            'password' => Hash::make('password123'),
            'profile' => '/public/images/profiles/pp.png',
            'title' => 'Pokémon Trainer',
            'xp' => 0
        ]);
    }
}
