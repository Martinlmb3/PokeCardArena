<?php

namespace App\Services;

class PokemonApiService
{
    private $baseUrl = 'https://pokeapi.co/api/v2/pokemon-species?limit=20';


    public function fetchPokemonNames(): array
    {
        $pokemonNames = [];

        $json_data = file_get_contents($this->baseUrl);

        if ($json_data === false) {
            return [];
        }
        $data = json_decode($json_data, true);

        if (isset($data['results']) && is_array($data['results'])) {
            foreach ($data['results'] as $pokemon) {
                if (isset($pokemon['name'])) {
                    $pokemonNames[] = $pokemon['name'];
                }
            }
        }

        return $pokemonNames;
    }
}
