<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/******HomePage*****/
Route::get('/', function () {
    return view('pokeView.index');
})->name('index');

/************Login*************/
Route::get('/login', function () {
    return view('pokeView.login');
})->name('login');

/************SignUp*************/
Route::get('/signup', function () {
    return view('pokeView.signup');
})->name('signup');

// Authenticated Routes Group
Route::middleware(['auth', 'verified'])->group(function () {
    /******Pokedex*****/
    Route::get('/pokedex/{id}', function ($id) {
        return view('pokeView.pokedex', ['id' => $id]);
    })->where('id', '[0-9]+')->name('pokedex');

    /******MyPokemon*****/
    Route::get('/myPokemon/{id}', function ($id) {
        return view('pokeView.myPokemon', ['id' => $id]);
    })->where('id', '[0-9]+')->name('my.pokemon');

    /******Profile*****/
    Route::get('/profile/{id}', [ProfileController::class, 'edit'])->where('id', '[0-9]+')->name('profile.edit');
    Route::patch('/profile/{id}', [ProfileController::class, 'update'])->where('id', '[0-9]+')->name('profile.update');

    /******PokemonCenter*****/
    Route::get('/pokemonCenter/{id}', function ($id) {
        return view('pokeView.pokemonCenter', ['id' => $id]);
    })->where('id', '[0-9]+')->name('pokemonCenter.show');
    Route::patch('/pokemonCenter/{id}', [ProfileController::class, 'add'])->where('id', '[0-9]+')->name('pokemon.center');
});

require __DIR__ . '/auth.php';
