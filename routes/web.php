<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/******pokedex*****/
Route::get('/dashboard/{id}', function ($id) {
    return view('pokedex', ['id' => $id]);
})->middleware(['auth', 'verified'])->where('id', '[0-9]+')->name('pokedex');

/******myPokemon*****/
Route::get('/myPokemon', function () {
    return view('myPokemon');
})->middleware(['auth', 'verified'])->name('myPokemon');

Route::middleware('auth')->group(function () {
    Route::get('pokeView/pokemaster/{id}', [ProfileController::class, 'edit'])->name('pokemaster.edit');
    Route::patch('pokeView/pokemaster/{id}', [ProfileController::class, 'update'])->name('pokemaster.update');
    Route::delete('pokeView/pokemaster/{id}', [ProfileController::class, 'destroy'])->name('pokemaster.destroy');
});

/******PokemonCenter*****/
Route::middleware('auth')->group(function () {
    Route::get('/pokemonCenter/{id}', function ($id) {
        return view('/pokemonCenter', ['id' => $id]);
    })->where('id', '[0-9]+')->name('pokemonCenter.show');

    Route::patch('/pokemonCenter/{id}', [ProfileController::class, 'add'])->name('pokemonCenter.add');
});

/******HomePage*****/
Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';
