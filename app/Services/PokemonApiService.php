<?php

namespace App\Services;

class PokemonApiService
{
    static private $baseUrl = 'https://pokeapi.co/api/v2/pokemon-species/';

    static public function genRandomId(): array
    {
        $idCollection = [];
        while (count($idCollection) < 20) {
            $id = rand(1, 1025);
            if (!in_array($id, $idCollection)) {
                $idCollection[] = $id;
            }
        }
        return $idCollection;
    }

    /**
     * Fetch 20 random Pokemon and return their details
     *
     * @return array An array of Pokemon details
     */
    public function fetchRandomPokemons(): array
    {
        $randomIds = self::genRandomId();
        $pokemonCollection = [];

        foreach ($randomIds as $id) {
            $pokemonDetails = $this->fetchPokemonDetails($id);
            $pokemonCollection[] = $pokemonDetails;
        }

        return $pokemonCollection;
    }

    public function fetchPokemonDetails($pokemonId): array
    {
        $json_data = file_get_contents($this->baseUrl . $pokemonId);

        if ($json_data === false) {
            return [
                'error' => 'Failed to fetch Pokemon details',
                'id' => $pokemonId
            ];
        }

        $data = json_decode($json_data, true);

        $details = [
            'id' => $data['id'],
            'name' => $data['name'] ,
            'types' => [],
            'image' => $data['sprites']['front_default'],
            'is_legendary' => $data['is_legendary'],
            'is_mythical' => $data['is_mythical']
        ];

        foreach ($data['types'] as $typeData) {
            if (isset($typeData['type']['name'])) {
                $details['types'][] = $typeData['type']['name'];
            }
        }

        return $details;
    }
}
