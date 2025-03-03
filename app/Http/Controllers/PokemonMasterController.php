<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Pokemaster;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PokemonMasterController extends Controller
{

    public function showLoginForm()
    {
        return view('pokeView.login');
    }
    public function showSignUpForm()
    {
        return view('pokeView.signUp');
    }

    public function doSignUpForm(SignUpRequest $request): \Illuminate\Routing\Redirector | \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->SignUpRequest::rules();
        $pokemaster =  Pokemaster::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'profile' =>  null
        ]);
        $request->session()->regenerate();
        return redirect()->route('pokedex.show');
    }

    public function doLoginForm(LoginRequest $request)
    {
        $pokemaster = $request->LoginRequest::rules();

        if (Auth::attempt($pokemaster)) {
            $request->session()->regenerate();
            $userId = Auth::id();
            return redirect()->route('pokeView.pokedex', ['id' => $userId]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }
}
