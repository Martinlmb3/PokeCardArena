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
Route::get('/pokedex', function () {
    return Inertia::render('Pokedex');
})->name('pokedex');

Route::get('/pokemonCenter', function () {
    return Inertia::render('PokemonCenter');
})->name('pokemonCenter');

Route::get('/myPokemon', function () {
    return Inertia::render('MyPokemon');
})->name('myPokemon');

Route::get('/profile', function () {
    return Inertia::render('Profile');
})->name('profile');

Route::post('/logout', [PokemonMasterController::class, 'logout'])->name('logout');

// Authenticated Routes Group
Route::middleware(['auth', 'verified'])->group(function () {
    /******Profile*****/
    Route::patch('/profile', [PokemonMasterController::class, 'update'])->name('profile.update');
    
    /******PokemonCenter*****/
    Route::patch('/pokemonCenter', [PokemonMasterController::class, 'add'])->name('pokemon.center');
});

require __DIR__ . '/auth.php';
