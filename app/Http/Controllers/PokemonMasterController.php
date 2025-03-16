<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Trainer;
use App\Models\pokedex;
use App\Models\pokemon;
use App\Services\PokemonApiService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PokemonMasterController extends Controller
{
    protected $pokemonService;

    public function __construct(PokemonApiService $pokemonService)
    {
        $this->pokemonService = $pokemonService;
    }
    public function showIndex() {
        return view('pokeView.index');
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

    public function showLoginForm()
    {
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
    public function showPokemon(Request $request, $id)
    {
        // Get random Pokémon collection
        $randomPokemons = $this->pokemonService->fetchRandomPokemons();
        $viewData = ['randomPokemons' => $randomPokemons];

        if ($request->has('pokemon_id')) {
            $pokemonId = $request->input('pokemon_id');
            $trainerId = $id;

            if (Auth::id() != $trainerId) {
                return redirect()->route('pokemonCenter', ['id' => Auth::id()])->with('error', 'Unauthorized access');
            }

            try {
                $pokemonDetails = DB::table('trainers')
                    ->join('pokedexes', 'trainers.id', '=', 'pokedexes.trainer_id')
                    ->leftJoin('pokemon', function ($join) use ($pokemonId) {
                        $join->on('pokedexes.id', '=', 'pokemon.pokedex_id')
                            ->where('pokemon.id', '=', $pokemonId);
                    })
                    ->where('trainers.id', '=', $trainerId)
                    ->select(
                        'trainers.name as trainer_name',
                        'trainers.id as trainer_id',
                        'pokemon.id as pokemon_id',
                        'pokemon.name as pokemon_name',
                        'pokemon.type as pokemon_type',
                        'pokemon.image as pokemon_image'
                    )
                    ->first();

                if (!$pokemonDetails || !isset($pokemonDetails->pokemon_name)) {
                    $selectedPokemon = $this->pokemonService->fetchPokemonDetails($pokemonId);
                    $viewData['selectedPokemon'] = $selectedPokemon;
                } else {
                    $viewData['selectedPokemon'] = [
                        'id' => $pokemonDetails->pokemon_id,
                        'name' => $pokemonDetails->pokemon_name,
                        'image' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$pokemonDetails->pokemon_id}.png",
                        'types' => [$pokemonDetails->pokemon_type],
                        'level' => $pokemonDetails->pokemon_level ?? 1
                    ];
                }
            } catch (\Exception $e) {
                $viewData['error'] = 'Error retrieving Pokemon details: ' . $e->getMessage();
            }
        }

        return view('pokeView.pokemonCenter', $viewData);
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
            'title' => 'Pokémon Trainer',
            'xp' => 0
        ]);

        if ($trainer) {
            Auth::login($trainer);
            return redirect()->route('pokedex', ['id' => $trainer->id]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->onlyInput('email');
    }


    public function fetchTrainerProfile($id)
    {
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

    public function selectPokemon(Request $request)
    {
        $pokemonId = $request->input('pokemon_id');

        $trainerId = Auth::id();

        if (!$trainerId || !$pokemonId) {
            return redirect()->back()->with('error', 'Missing trainer or Pokemon information');
        }

        // Perform database join to get trainer's pokedex and the selected Pokemon
        $pokemonDetails = DB::table('trainers')
            ->join('pokedexes', 'trainers.id', '=', 'pokedexes.trainer_id')
            ->leftJoin('pokemon', 'pokemon.id', '=', $pokemonId)
            ->where('trainers.id', '=', $trainerId)
            ->select(
                'trainers.name as trainer_name',
                'trainers.id as trainer_id',
                'pokemon.id as pokemon_id',
                'pokemon.name as pokemon_name',
                'pokemon.type as pokemon_type',
                'pokemon.image as pokemon_image',
            )
            ->first();

        // If the Pokemon isn't found, fetch the details from the API
        if (!$pokemonDetails || !$pokemonDetails->pokemon_name) {
            $pokemonInfo = $this->pokemonService->fetchPokemonDetails($pokemonId);

            // Create a view to display the Pokemon details
            return view('pokeView.pokemonDetails', [
                'pokemon' => $pokemonInfo,
                'pokemon_id' => $pokemonId,
                'trainer_id' => $trainerId
            ]);
        }

        return view('pokeView.pokemonDetails', [
            'pokemonDetails' => $pokemonDetails,
            'pokemon_id' => $pokemonId,
            'trainer_id' => $trainerId
        ]);
    }

    public function update(Request $request, $id): \Illuminate\Routing\Redirector | \Illuminate\Http\RedirectResponse
    {

        if (Auth::id() != $id) {
            return redirect()->route('profile', ['id' => Auth::id()])->with('error', 'You cannot update other users\' profiles');
        }

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('trainers')->ignore($id)
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $trainer = Trainer::findOrFail($id);

        $trainer->name = $validatedData['name'];
        $trainer->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            if ($validatedData['password'] !== $validatedData['password_confirmation']) {
                return back()->withErrors([
                    'password' => 'Passwords do not match',
                ]);
            }
            $trainer->password = Hash::make($validatedData['password']);
        }

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $filename = time() . '.' . $profileImage->getClientOriginalExtension();

            $path = $profileImage->storeAs('public/images/profiles', $filename);

            if ($path === false) {
                return redirect()->back()->with('error', 'Failed to upload profile image. Please try again.');
            }

            $trainer->profile = Storage::url('images/profiles/' . $filename);
        }

        $trainer->save();

        return redirect()->route('profile', ['id' => $id])->with('success', 'Profile updated successfully');
    }
}
