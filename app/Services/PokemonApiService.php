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
    
    public function fetchPokemonDetails($pokemonId): array
    {
        $url = "https://pokeapi.co/api/v2/pokemon/{$pokemonId}/";
        
        $json_data = file_get_contents($url);
        
        if ($json_data === false) {
            return [
                'error' => 'Failed to fetch Pokemon details',
                'id' => $pokemonId
            ];
        }
        
        $data = json_decode($json_data, true);
        
        $details = [
            'id' => $data['id'] ?? $pokemonId,
            'name' => $data['name'] ?? 'Unknown',
            'image' => $data['sprites']['front_default'] ?? null,
            'height' => $data['height'] ?? 0,
            'weight' => $data['weight'] ?? 0,
            'types' => []
        ];
        
        if (isset($data['types']) && is_array($data['types'])) {
            foreach ($data['types'] as $typeData) {
                if (isset($typeData['type']['name'])) {
                    $details['types'][] = $typeData['type']['name'];
                }
            }
        }
        
        // Get additional species information
        $speciesUrl = $data['species']['url'] ?? null;
        if ($speciesUrl) {
            $speciesData = file_get_contents($speciesUrl);
            if ($speciesData !== false) {
                $species = json_decode($speciesData, true);
                
                // Add English flavor text (description)
                if (isset($species['flavor_text_entries']) && is_array($species['flavor_text_entries'])) {
                    foreach ($species['flavor_text_entries'] as $entry) {
                        if (isset($entry['language']['name']) && $entry['language']['name'] === 'en') {
                            $details['description'] = $entry['flavor_text'] ?? '';
                            break;
                        }
                    }
                }
            }
        }
        
        return $details;
    }
}
