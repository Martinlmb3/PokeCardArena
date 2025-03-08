<?php


use App\Http\Controllers\PokemonMasterController;
use Illuminate\Support\Facades\Route;

/******HomePage*****/
Route::get('/', function () {
    return view('pokeView.index');
})->name('index');

/************Login*************/
Route::middleware('guest')->group(function () {
    Route::get('/login', [PokemonMasterController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PokemonMasterController::class, 'doLoginForm'])->name('login.submit');
});

/************SignUp*************/
Route::get('/signup', [PokemonMasterController::class, 'showSignUpForm'])->name('signUp');
Route::post('/signup', [PokemonMasterController::class, 'doSignUpForm'])->name('signUp.submit');

//
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
