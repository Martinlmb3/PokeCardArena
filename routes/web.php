<?php


use App\Http\Controllers\PokemonMasterController;
use Illuminate\Support\Facades\Route;

/******HomePage*****/
Route::get('/', function () {
    return view('pokeView.index');
})->name('index');

/************Login*************/
Route::get('/login', [PokemonMasterController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PokemonMasterController::class, 'doLoginForm'])->name('login.submit');

/************SignUp*************/
Route::get('/signup', [PokemonMasterController::class, 'showSignUpForm'])->name('signUp');
Route::post('/signup', [PokemonMasterController::class, 'doSignUpForm'])->name('signUp.submit');
//
// Authenticated Routes Group
Route::controller(PokemonMasterController::class)->group(function () {
    /******Pokedex*****/
    Route::get('/pokedex/{id}', function ($id) {
        return view('pokeView.pokedex', ['id' => $id]);
    })->where('id', '[0-9]+')->name('pokedex');
    /******MyPokemon*****/
    Route::get('/myPokemon/{id}', function ($id) {
        return view('pokeView.myPokemon', ['id' => $id]);
    })->where('id', '[0-9]+')->name('my.pokemon');

    /******Profile*****/
    Route::get('/profile/{id}', [PokemonMasterController::class, 'showProfile'])->where('id', '[0-9]+')->name('profile');
    Route::patch('/profile/{id}', [PokemonMasterController::class, 'update'])->where('id', '[0-9]+')->name('profile.update');

    /******PokemonCenter*****/
    /* Route::get('/pokemonCenter/{id}', function ($id) {
        return view('pokeView.pokemonCenter', ['id' => $id]);
    })->where('id', '[0-9]+')->name('pokemonCenter.show');*/
    Route::get('/pokemonCenter/{id}', [PokemonMasterController::class, 'showPokemon'])->where('id', '[0-9]+');

    Route::post('/pokemon/discover', [PokemonMasterController::class, 'discoverPokemon'])->name('pokemon.discover');
});
/*******Logout**** */
Route::post('/login', [PokemonMasterController::class, 'logout'])->name('logout');

require __DIR__ . '/auth.php';
