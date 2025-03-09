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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

    public function update(Request $request, $id)
    {
        // Validate user can only update their own profile
        if (Auth::id() != $id) {
            return redirect()->route('profile', ['id' => Auth::id()])->with('error', 'You cannot update other users\' profiles');
        }

        // Validate input
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
            $trainer->password = Hash::make($validatedData['password']);
        }

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $filename = time() . '.' . $profileImage->getClientOriginalExtension();

            $path = $profileImage->storeAs('public/images/profiles', $filename);

            $trainer->profile = '/public/images/profiles/' . $filename;
        }

        $trainer->save();

        return redirect()->route('profile', ['id' => $id])->with('success', 'Profile updated successfully');
    }
}
