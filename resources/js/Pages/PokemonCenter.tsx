import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface Pokemon {
    id: number;
    name: string;
    type: string;
    image?: string;
    color: string;
}

interface PokemonCenterProps {
    pokemon?: Pokemon[];
}

export default function PokemonCenter({ pokemon = [] }: PokemonCenterProps) {
    const { data, setData, get, processing } = useForm({
        search: '',
        type: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        get('/pokemonCenter');
    };

    const pokemonTypes = [
        'fire', 'water', 'grass', 'electric', 'ice', 'fighting',
        'poison', 'ground', 'flying', 'psychic', 'bug', 'rock',
        'ghost', 'dragon', 'dark', 'steel', 'fairy'
    ];

    const samplePokemon: Pokemon[] = [
        { id: 1, name: 'Name', type: 'Type', color: 'bg-green-500' },
        { id: 2, name: 'Name', type: 'Type', color: 'bg-blue-950' },
        { id: 3, name: 'Name', type: 'Type', color: 'bg-purple-500' },
        { id: 4, name: 'Name', type: 'Type', color: 'bg-orange-200' },
        { id: 5, name: 'Name', type: 'Type', color: 'bg-yellow-200' },
        { id: 6, name: 'Name', type: 'Type', color: 'bg-pink-100' },
        { id: 7, name: 'Name', type: 'Type', color: 'bg-orange-200' },
        { id: 8, name: 'Name', type: 'Type', color: 'bg-yellow-200' },
        { id: 9, name: 'Name', type: 'Type', color: 'bg-pink-100' },
        { id: 10, name: 'Name', type: 'Type', color: 'bg-orange-200' },
        { id: 11, name: 'Name', type: 'Type', color: 'bg-yellow-200' },
        { id: 12, name: 'Name', type: 'Type', color: 'bg-pink-100' },
    ];

    const displayPokemon = pokemon.length > 0 ? pokemon : samplePokemon;

    return (
        <Layout>
            <Head title="Pokémon Center - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <article className="flex m-auto p-5 rounded bg-white w-full max-w-3xl">
                    <form onSubmit={handleSubmit} className="flex flex-col sm:flex-row items-center gap-4 w-full">
                        <div className="form-group w-full sm:flex-1">
                            <label htmlFor="search" className="sr-only">Search Pokémon</label>
                            <input 
                                type="text"
                                className="form-control w-full"
                                name="search"
                                id="search"
                                placeholder="Search Pokémon..."
                                value={data.search}
                                onChange={(e) => setData('search', e.target.value)}
                            />
                        </div>
                        <div className="form-group w-full sm:w-48">
                            <label htmlFor="type" className="sr-only">Type</label>
                            <select 
                                className="form-control w-full" 
                                name="type" 
                                id="type"
                                value={data.type}
                                onChange={(e) => setData('type', e.target.value)}
                            >
                                <option value="" disabled>Select Type</option>
                                {pokemonTypes.map(type => (
                                    <option key={type} value={type}>
                                        {type.charAt(0).toUpperCase() + type.slice(1)}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <button 
                            type="submit" 
                            className="btn btn-primary w-full sm:w-32"
                            disabled={processing}
                        >
                            {processing ? 'Searching...' : 'Search'}
                        </button>
                    </form>
                </article>
                <section className="flex flex-col sm:flex-row sm:flex-wrap mt-5 mx-auto max-w-4xl bg-white justify-evenly shadow-xl p-4">
                    {displayPokemon.map((poke) => (
                        <Link 
                            key={poke.id}
                            href={`/pokemon/${poke.id}`} 
                            className="w-full sm:w-auto flex justify-center"
                        >
                            <article className={`w-full sm:w-48 h-60 my-2 sm:m-5 ${poke.color} p-4 rounded`}>
                                <p className="font-semibold">{poke.name}</p>
                                <p className="text-sm">{poke.type}</p>
                                {poke.image && (
                                    <img src={poke.image} alt={`${poke.name} pokémon`} className="w-full h-32 object-contain mt-2" />
                                )}
                            </article>
                        </Link>
                    ))}
                </section>
            </main>
        </Layout>
    );
}