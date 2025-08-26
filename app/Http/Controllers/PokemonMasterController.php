<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Pokemaster;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PokemonMasterController extends Controller
{

    public function showLoginForm()
    {
        return Inertia::render('Login');
    }
    
    public function showSignUpForm()
    {
        return Inertia::render('Signup');
    }

    public function doSignUpForm(SignUpRequest $request)
    {
        $validatedData = $request->validated();
        
        $pokemaster = Pokemaster::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'profile' => null
        ]);

        Auth::login($pokemaster);
        $request->session()->regenerate();

        return redirect()->route('pokedex');
    }

    public function doLoginForm(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('pokedex');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('index');
    }
}
