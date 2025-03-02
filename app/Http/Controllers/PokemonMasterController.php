<?php

namespace App\Http\Controllers;

use App\Models\Pokemaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PokemonMasterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pokemasters,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $pokemaster = Pokemaster::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::auth()->login($pokemaster);

        return redirect()->route('pokedex.show')->with('success', 'Registration successful!');
    }
}
