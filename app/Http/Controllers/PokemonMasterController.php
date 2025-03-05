<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\PokemonMaster;
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
        $validatedData = $request->validated();
        $pokeMaster = PokemonMaster::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'profile' => '/public/images/profiles/pp.png',
            'xp' => 50,
            'title' => 'Pokémon Trainer'
        ]);

        if ($pokeMaster) {
            session(['user_id' => $pokeMaster->id]);
            $request->session()->regenerate();
            return redirect()->route('pokeView.pokedex', ['id' => $pokeMaster->id]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->onlyInput('email');
    }

    public function doLoginForm(LoginRequest $request): \Illuminate\Routing\Redirector | \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            session(['user_id' => Auth::id()]);
            return redirect()->route('pokeView.pokedex', ['id' => Auth::id()]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
