<?php

namespace App\Services;

class PokemonApiService
{
    static private $baseUrl = 'https://pokeapi.co/api/v2/pokemon/';
    static private $speciesUrl = 'https://pokeapi.co/api/v2/pokemon-species/';

    static public function genRandomId($count = 12): array
    {
        $idCollection = [];
        while (count($idCollection) < $count) {
            $id = rand(1, 1025);
            if (!in_array($id, $idCollection)) {
                $idCollection[] = $id;
            }
        }
        return $idCollection;
    }

    /**
     * Fetch 12 random Pokemon and return their details
     *
     * @return array An array of Pokemon details
     */
    public function fetchRandomPokemons(): array
    {
        $randomIds = self::genRandomId(12);
        $pokemonCollection = [];

        foreach ($randomIds as $id) {
            $pokemonDetails = $this->fetchPokemonDetails($id);
            if (!isset($pokemonDetails['error'])) {
                $pokemonCollection[] = $pokemonDetails;
            }
        }

        return $pokemonCollection;
    }

    public function fetchPokemonDetails($pokemonId): array
    {
        // Fetch basic pokemon data
        $json_data = file_get_contents(self::$baseUrl . $pokemonId);

        if ($json_data === false) {
            return [
                'error' => 'Failed to fetch Pokemon details',
                'id' => $pokemonId
            ];
        }

        $data = json_decode($json_data, true);

        // Fetch species data for legendary/mythical info
        $species_json = file_get_contents(self::$speciesUrl . $pokemonId);
        $species_data = $species_json ? json_decode($species_json, true) : null;

        $types = [];
        foreach ($data['types'] as $typeData) {
            if (isset($typeData['type']['name'])) {
                $types[] = $typeData['type']['name'];
            }
        }

        $details = [
            'id' => $data['id'],
            'name' => ucfirst($data['name']),
            'types' => $types,
            'image' => $data['sprites']['front_default'],
            'is_legendary' => $species_data ? $species_data['is_legendary'] : false,
            'is_mythical' => $species_data ? $species_data['is_mythical'] : false
        ];

        return $details;
    }
}
