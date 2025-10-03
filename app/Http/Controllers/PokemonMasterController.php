<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Pokemaster;
use App\Models\Pokemon;
use App\Models\Pokedex;
use App\Services\PokemonApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Carbon\Carbon;

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

    public function update(Request $request)
    {
        $user = auth()->user();


        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('pokemasters')->ignore($user->id)
            ],
            'password' => 'nullable|min:6|confirmed',
            'profilePicture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update name and email if provided
        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Handle profile picture upload
        if ($request->hasFile('profilePicture')) {
            $file = $request->file('profilePicture');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

            // Ensure the directory exists
            $directory = public_path('images/profiles');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Store in public/images/profiles/ directory
            $file->move($directory, $filename);

            // Update user profile with new filename
            $user->profile = '/images/profiles/' . $filename;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function pokedex()
    {
        $user = auth()->user();
        
        // Get pokemon counts from the pokemon table linked via pokedexes
        $pokemonCounts = DB::table('pokemon')
            ->join('pokedexes', 'pokemon.pokedex_id', '=', 'pokedexes.id')
            ->where('pokedexes.pokemaster_id', $user->id)
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN pokemon.is_mythical = 'true' THEN 1 ELSE 0 END) as mythical"),
                DB::raw("SUM(CASE WHEN pokemon.is_legendary = 'true' THEN 1 ELSE 0 END) as legendary")
            )
            ->first();

        $userData = [
            'name' => $user->name,
            'title' => $user->title ?? 'Rookie Trainer',
            'experience' => $user->xp ?? 0,
            'experienceToNext' => 1500 - ($user->xp ?? 0),
            'totalPokemon' => $pokemonCounts->total ?? 0,
            'mythicalCount' => $pokemonCounts->mythical ?? 0,
            'legendaryCount' => $pokemonCounts->legendary ?? 0,
            'profileImage' => $user->profile ?? '/images/profiles/pp.png'
        ];

        return Inertia::render('Pokedex', [
            'user' => $userData
        ]);
    }

    public function pokemonCenter()
    {
        $user = auth()->user();
        // Initialize default values if not set
        if (!$user->xp || !$user->title) {
            $user->update([
                'xp' => $user->xp ?? 0,
                'title' => $user->title ?? 'Rookie Trainer'
            ]);
        }

        // Check how many pokemon caught today
        $today = Carbon::today();
        $pokemonCaughtToday = Pokemon::join('pokedexes', 'pokemon.pokedex_id', '=', 'pokedexes.id')
            ->where('pokedexes.pokemaster_id', $user->id)
            ->whereDate('pokemon.capture_at', $today)
            ->count();

        // Fetch 12 random pokemon from API
        $apiService = new PokemonApiService();
        $randomPokemon = $apiService->fetchRandomPokemons();

        return Inertia::render('PokemonCenter', [
            'pokemon' => $randomPokemon,
            'dailyLimit' => 4,
            'pokemonCaughtToday' => $pokemonCaughtToday,
            'remainingCatches' => max(0, 4 - $pokemonCaughtToday)
        ]);
    }

    public function catchPokemon(Request $request)
    {
        $user = auth()->user();
        // User is already authenticated pokemaster

        // Check daily limit
        $today = Carbon::today();
        $pokemonCaughtToday = Pokemon::join('pokedexes', 'pokemon.pokedex_id', '=', 'pokedexes.id')
            ->where('pokedexes.pokemaster_id', $user->id)
            ->whereDate('pokemon.capture_at', $today)
            ->count();

        if ($pokemonCaughtToday >= 4) {
            return redirect()->back()->withErrors([
                'error' => 'Daily limit reached! You can only catch 4 Pokemon per day.'
            ]);
        }

        // Get pokemon data from request
        $pokemonData = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string',
            'image' => 'required|string',
            'types' => 'required|array',
            'is_legendary' => 'required|boolean',
            'is_mythical' => 'required|boolean',
        ]);

        // Find or create pokedex entry for this trainer
        $pokedex = Pokedex::firstOrCreate(
            ['pokemaster_id' => $user->id],
            [
                'pokemaster_id' => $user->id,
                'nbPokemon' => 0,
                'nbPokemonMythic' => 0,
                'nbPokemonLeg' => 0,
                'pokemon_id' => null // This will be set to the first caught pokemon
            ]
        );

        // Save pokemon to database
        $pokemon = Pokemon::create([
            'name' => $pokemonData['name'],
            'image' => $pokemonData['image'],
            'is_legendary' => $pokemonData['is_legendary'] ? 'true' : 'false',
            'is_mythical' => $pokemonData['is_mythical'] ? 'true' : 'false',
            'capture_at' => Carbon::now(),
            'pokedex_id' => $pokedex->id
        ]);

        // If this is the first pokemon for this pokedex, update the pokemon_id reference
        if ($pokedex->pokemon_id === null) {
            $pokedex->pokemon_id = $pokemon->id;
            $pokedex->save();
        }

        // Update pokedex counters
        $pokedex->increment('nbPokemon');
        if ($pokemonData['is_legendary']) {
            $pokedex->increment('nbPokemonLeg');
        }
        if ($pokemonData['is_mythical']) {
            $pokedex->increment('nbPokemonMythic');
        }

        // Update trainer XP (bonus for legendary/mythical)
        $xpGain = 10;
        if ($pokemonData['is_legendary']) $xpGain += 50;
        if ($pokemonData['is_mythical']) $xpGain += 100;
        
        $user->increment('xp', $xpGain);

        // For Inertia, we need to redirect back with success data
        return redirect()->back()->with([
            'success' => "Caught {$pokemon->name}! Gained {$xpGain} XP!",
            'pokemon' => $pokemon,
            'xpGained' => $xpGain,
            'remainingCatches' => max(0, 3 - $pokemonCaughtToday)
        ]);
    }

    public function myPokemon()
    {
        $user = auth()->user();
        if (!$user) {
            // If not authenticated, show empty pokedex
            return Inertia::render('MyPokemon', [
                'userPokemon' => [],
                'totalCaught' => 0,
                'totalMythical' => 0,
                'totalLegendary' => 0
            ]);
        }

        // Get all Pokemon caught by this trainer
        $userPokemon = DB::table('pokemon')
            ->join('pokedexes', 'pokemon.pokedex_id', '=', 'pokedexes.id')
            ->where('pokedexes.pokemaster_id', $user->id)
            ->select(
                'pokemon.*',
                DB::raw('DATE(pokemon.capture_at) as capture_date')
            )
            ->orderBy('pokemon.capture_at', 'desc')
            ->get()
            ->map(function ($pokemon) {
                return [
                    'id' => $pokemon->id,
                    'name' => ucfirst($pokemon->name),
                    'image' => $pokemon->image,
                    'is_legendary' => $pokemon->is_legendary === 'true',
                    'is_mythical' => $pokemon->is_mythical === 'true',
                    'capture_date' => $pokemon->capture_date,
                    'capture_at' => $pokemon->capture_at
                ];
            });

        // Calculate statistics
        $totalCaught = $userPokemon->count();
        $totalMythical = $userPokemon->where('is_mythical', true)->count();
        $totalLegendary = $userPokemon->where('is_legendary', true)->count();

        return Inertia::render('MyPokemon', [
            'userPokemon' => $userPokemon->values()->toArray(),
            'totalCaught' => $totalCaught,
            'totalMythical' => $totalMythical,
            'totalLegendary' => $totalLegendary,
            'trainerName' => $user->name
        ]);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('index');
    }
}
