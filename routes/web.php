<?php


use App\Http\Controllers\PokemonMasterController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->controller(PokemonMasterController::class)->group(function () {
    /******HomePage*****/
    Route::get('/', 'showIndex')->name('index');
    /************Login*************/
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'doLoginForm')->name('login.submit');
    /************SignUp*************/
    Route::get('/signup', 'showSignUpForm')->name('signUp');
    Route::post('/signup', 'doSignUpForm')->name('signUp.submit');
});


// Authenticated Routes Group
Route::middleware(['auth'])->controller(PokemonMasterController::class)->group(function () {
    /******Pokedex*****/
    Route::get('/pokedex/{id}', 'showPokedex')->where('id', '[0-9]+')->name('pokedex');

    /******MyPokemon*****/
    Route::get('/myPokemon/{id}', 'showTrainerPokemon')->where('id', '[0-9]+')->name('my.pokemon');

    /******Profile*****/
    Route::get('/profile/{id}', 'fetchTrainerProfile')->where('id', '[0-9]+')->name('profile');
    Route::patch('/profile/{id}', 'update')->where('id', '[0-9]+')->name('profile.update');

    /******PokemonCenter*****/
    Route::get('/pokemonCenter/{id}', 'showPokemon')->where('id', '[0-9]+')->name('pokemonCenter');

    /*******Logout**** */
    Route::post('/logout', 'logout')->name('logout');
});

require __DIR__ . '/auth.php';
