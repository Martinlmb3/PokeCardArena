<?php


use App\Http\Controllers\PokemonMasterController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\PokedexController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/******HomePage*****/
Route::get('/', function () {
    return Inertia::render('Index');
})->name('index');

/************Login*************/
Route::get('/login', [PokemonMasterController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PokemonMasterController::class, 'doLoginForm'])->name('login');
/************SignUp*************/
Route::get('/signup', [PokemonMasterController::class, 'showSignUpForm'])->name('signUp');
Route::post('/signup', [PokemonMasterController::class, 'doSignUpForm'])->name('signUp');
//
// Public routes for unauthenticated pages
Route::get('/pokedex', [PokemonMasterController::class, 'pokedex'])->name('pokedex');

Route::get('/pokemonCenter', [PokemonMasterController::class, 'pokemonCenter'])->name('pokemonCenter')->middleware('auth');

Route::get('/myPokemon', [PokemonMasterController::class, 'myPokemon'])->name('myPokemon')->middleware('auth');

Route::get('/profile', function () {
    return Inertia::render('Profile', [
        'user' => auth()->user()
    ]);
})->name('profile')->middleware('auth');

Route::post('/logout', [PokemonMasterController::class, 'logout'])->name('logout');

// Authenticated Routes Group
Route::middleware(['auth', 'verified'])->group(function () {
    /******Profile*****/
    Route::patch('/profile', [PokemonMasterController::class, 'update'])->name('profile.update');
    
    /******PokemonCenter*****/
    Route::post('/pokemon/catch', [PokemonMasterController::class, 'catchPokemon'])->name('pokemon.catch');
});

require __DIR__ . '/auth.php';
