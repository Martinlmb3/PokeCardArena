import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface Pokemon {
    id: number;
    name: string;
    types: string[];
    image: string;
    is_legendary: boolean;
    is_mythical: boolean;
}

interface PokemonCenterProps {
    pokemon: Pokemon[];
    dailyLimit: number;
    pokemonCaughtToday: number;
    remainingCatches: number;
    success?: string;
    errors?: Record<string, string>;
}

export default function PokemonCenter({ pokemon, dailyLimit, pokemonCaughtToday, remainingCatches, success, errors }: PokemonCenterProps) {
    const [revealedPokemon, setRevealedPokemon] = useState<number[]>([]);
    const [caughtPokemon, setCaughtPokemon] = useState<number[]>([]);
    const [currentRemaining, setCurrentRemaining] = useState(remainingCatches);
    const { post, processing } = useForm();

    const handlePokemonClick = (clickedPokemon: Pokemon) => {
        if (caughtPokemon.includes(clickedPokemon.id) || currentRemaining <= 0) {
            return;
        }

        // Reveal the pokemon first
        if (!revealedPokemon.includes(clickedPokemon.id)) {
            setRevealedPokemon(prev => [...prev, clickedPokemon.id]);
            
            // After revealing, catch the pokemon
            setTimeout(() => {
                catchPokemon(clickedPokemon);
            }, 500);
        } else if (!caughtPokemon.includes(clickedPokemon.id)) {
            catchPokemon(clickedPokemon);
        }
    };

    const catchPokemon = (pokemon: Pokemon) => {
        router.post('/pokemon/catch', {
            id: pokemon.id,
            name: pokemon.name,
            image: pokemon.image,
            types: pokemon.types,
            is_legendary: pokemon.is_legendary,
            is_mythical: pokemon.is_mythical
        }, {
            onSuccess: () => {
                setCaughtPokemon(prev => [...prev, pokemon.id]);
                setCurrentRemaining(prev => prev - 1);
                // Success message will be shown via flash data
            },
            onError: () => {
                // Error message will be shown via errors prop
            }
        });
    };

    const getTypeColor = (types: string[]) => {
        const typeColors: Record<string, string> = {
            fire: 'bg-red-500',
            water: 'bg-blue-500',
            grass: 'bg-green-500',
            electric: 'bg-yellow-400',
            ice: 'bg-blue-200',
            fighting: 'bg-red-700',
            poison: 'bg-purple-500',
            ground: 'bg-yellow-600',
            flying: 'bg-indigo-400',
            psychic: 'bg-pink-500',
            bug: 'bg-green-400',
            rock: 'bg-gray-600',
            ghost: 'bg-purple-700',
            dragon: 'bg-indigo-700',
            dark: 'bg-gray-800',
            steel: 'bg-gray-400',
            fairy: 'bg-pink-300',
            normal: 'bg-gray-400'
        };
        return typeColors[types[0]] || 'bg-gray-500';
    };

    return (
        <Layout>
            <Head title="Pokémon Center - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <div className="flex m-auto p-5 rounded bg-white w-full max-w-4xl mb-5">
                    <div className="w-full text-center">
                        <h1 className="text-2xl font-bold mb-4">Welcome to the Pokémon Center!</h1>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div className="bg-blue-100 p-3 rounded">
                                <p className="font-semibold">Daily Limit</p>
                                <p className="text-2xl text-blue-600">{dailyLimit}</p>
                            </div>
                            <div className="bg-green-100 p-3 rounded">
                                <p className="font-semibold">Caught Today</p>
                                <p className="text-2xl text-green-600">{pokemonCaughtToday}</p>
                            </div>
                            <div className="bg-orange-100 p-3 rounded">
                                <p className="font-semibold">Remaining</p>
                                <p className="text-2xl text-orange-600">{currentRemaining}</p>
                            </div>
                        </div>
                        {currentRemaining <= 0 && (
                            <div className="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                You've reached your daily limit! Come back tomorrow for more Pokémon.
                            </div>
                        )}
                        {success && (
                            <div className="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {success}
                            </div>
                        )}
                        {errors?.error && (
                            <div className="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {errors.error}
                            </div>
                        )}
                    </div>
                </div>
                <section className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mx-auto max-w-6xl">
                    {pokemon.map((poke) => {
                        const isRevealed = revealedPokemon.includes(poke.id);
                        const isCaught = caughtPokemon.includes(poke.id);
                        const canClick = currentRemaining > 0 && !isCaught;
                        
                        return (
                            <div 
                                key={poke.id}
                                onClick={() => canClick && handlePokemonClick(poke)}
                                className={`relative w-full h-64 rounded-lg shadow-lg transition-all duration-500 transform hover:scale-105 ${
                                    canClick ? 'cursor-pointer' : 'cursor-not-allowed'
                                } ${
                                    isCaught ? 'border-4 border-green-500' : ''
                                }`}
                            >
                                {!isRevealed ? (
                                    // Blacked out Pokemon
                                    <div className="w-full h-full bg-black rounded-lg flex items-center justify-center">
                                        <div className="text-white text-center">
                                            <div className="w-16 h-16 bg-gray-800 rounded-full mx-auto mb-2 flex items-center justify-center">
                                                <span className="text-2xl">?</span>
                                            </div>
                                            <p className="text-sm">Click to reveal!</p>
                                        </div>
                                    </div>
                                ) : (
                                    // Revealed Pokemon
                                    <div className={`w-full h-full ${getTypeColor(poke.types)} rounded-lg p-4 relative overflow-hidden`}>
                                        <div className="absolute top-2 right-2">
                                            {poke.is_legendary && <span className="bg-orange-500 text-white px-2 py-1 rounded text-xs">Legendary</span>}
                                            {poke.is_mythical && <span className="bg-purple-500 text-white px-2 py-1 rounded text-xs ml-1">Mythical</span>}
                                        </div>
                                        <h3 className="font-bold text-white text-lg mb-1 capitalize">{poke.name}</h3>
                                        <p className="text-white text-sm mb-2 capitalize">{poke.types.join(', ')}</p>
                                        <div className="flex justify-center items-center h-32">
                                            <img 
                                                src={poke.image} 
                                                alt={`${poke.name} pokémon`} 
                                                className="max-w-full max-h-full object-contain filter drop-shadow-lg"
                                                onError={(e) => {
                                                    (e.target as HTMLImageElement).style.display = 'none';
                                                }}
                                            />
                                        </div>
                                        {isCaught && (
                                            <div className="absolute inset-0 bg-green-500 bg-opacity-75 rounded-lg flex items-center justify-center">
                                                <div className="text-white text-center">
                                                    <div className="text-4xl mb-2">✓</div>
                                                    <p className="font-bold">CAUGHT!</p>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                )}
                            </div>
                        );
                    })}
                </section>
            </main>
        </Layout>
    );
}