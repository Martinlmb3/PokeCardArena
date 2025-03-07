<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\PokemonMaster;
use App\Services\PokemonApiService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\DiscoveredPokemon;

class PokemonMasterController extends Controller
{
    protected $pokemonService;

    public function __construct(PokemonApiService $pokemonService)
    {
        $this->pokemonService = $pokemonService;
    }

    public function showLoginForm()
    {
        return view('pokeView.login');
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
        $discoveredPokemon = DiscoveredPokemon::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pokeView.pokemonCenter', [
            'pokemons' => $pokemons,
            'discoveredPokemon' => $discoveredPokemon
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return to_route('pokeView.index');
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
            return redirect()->intended(route('pokedex', ['id' => Auth::id()]));
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->onlyInput('email');
    }

    public function doLoginForm(LoginRequest $request): \Illuminate\Routing\Redirector | \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validated();
        dd(Auth::attempt($credentials));
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            session(['user_id' => Auth::id()]);
            return redirect()->intended(route('pokedex', ['id' => Auth::id()]));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function discoverPokemon(Request $request)
    {
        try {
            $discovered = DiscoveredPokemon::create([
                'user_id' => Auth::id(),
                'pokemon_id' => $request->pokemon_id,
                'pokemon_name' => $request->pokemon_name,
                'sprite_url' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$request->pokemon_id}.png"
            ]);

            return response()->json([
                'success' => true,
                'pokemon' => $discovered
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record discovered Pokemon'
            ], 500);
        }
    }
}
