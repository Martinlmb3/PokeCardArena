<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Trainer;
use App\Services\PokemonApiService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class PokemonMasterController extends Controller
{
    protected $pokemonService;

    public function __construct(PokemonApiService $pokemonService)
    {
        $this->pokemonService = $pokemonService;
    }



    public function showSignUpForm()
    {
        return view('pokeView.signUp');
    }

    public function showProfile()
    {
        return view('pokeView.profile');
    }

    public function showTrainerPokemon()
    {
        return view('pokeView.myPokemon');
    }

    public function showPokemon()
    {
        $pokemons = $this->pokemonService->fetchPokemonNames();
        return view('pokeView.pokemonCenter', ['pokemons' => $pokemons]);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        Session::invalidate();
        Cookie::queue(Cookie::forget('laravel_session'));
        Cookie::queue(Cookie::forget('XSRF-TOKEN'));
        return redirect()->route('index')->with('success', 'You have been logged out successfully');
    }

    public function doSignUpForm(SignUpRequest $request): \Illuminate\Routing\Redirector | \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        $trainer = Trainer::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'profile' => '/public/images/profiles/pp.png',
            'title' => 'Pokémon Trainer'
        ]);

        if ($trainer) {
            Auth::login($trainer);
            return redirect()->route('pokedex', ['id' => $trainer->id]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->onlyInput('email');
    }
    public function showLoginForm()
    {
        dd('Route is working');
        return view('pokeView.login');
    }
    public function doLoginForm(LoginRequest $request): \Illuminate\Routing\Redirector | \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('pokedex', ['id' => Auth::id()]));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function fetchTrainerProfile($id){
        $trainer = Trainer::findOrFail($id);
        return view('pokeView.profile', [
            'name' => $trainer->name,
            'email' => $trainer->email
        ]);
    }

    public function showPokedex($id)
    {
        $trainer = Trainer::findOrFail($id);
        return view('pokeView.pokedex', [
            'user' => $trainer,
            'id' => $id
        ]);
    }
}
