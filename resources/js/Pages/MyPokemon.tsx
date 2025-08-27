import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface UserPokemon {
    id: number;
    name: string;
    image: string;
    is_legendary: boolean;
    is_mythical: boolean;
    capture_date: string;
    capture_at: string;
}

interface MyPokemonProps {
    userPokemon: UserPokemon[];
    totalCaught: number;
    totalMythical: number;
    totalLegendary: number;
    trainerName?: string;
}

export default function MyPokemon({ userPokemon, totalCaught, totalMythical, totalLegendary, trainerName }: MyPokemonProps) {
    const [searchTerm, setSearchTerm] = useState('');
    const [filterType, setFilterType] = useState('all');
    
    // Filter Pokemon based on search and type
    const filteredPokemon = userPokemon.filter(pokemon => {
        const matchesSearch = pokemon.name.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesType = filterType === 'all' || 
            (filterType === 'legendary' && pokemon.is_legendary) ||
            (filterType === 'mythical' && pokemon.is_mythical) ||
            (filterType === 'normal' && !pokemon.is_legendary && !pokemon.is_mythical);
        return matchesSearch && matchesType;
    });

    const getTypeColor = (pokemon: UserPokemon) => {
        if (pokemon.is_mythical) return 'bg-purple-500';
        if (pokemon.is_legendary) return 'bg-orange-500';
        return 'bg-blue-500';
    };
    
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString();
    };

    const filterTypes = [
        { value: 'all', label: 'All Pokemon' },
        { value: 'normal', label: 'Normal' },
        { value: 'legendary', label: 'Legendary' },
        { value: 'mythical', label: 'Mythical' }
    ];


    return (
        <Layout>
            <Head title="My Pokémon - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                {/* Header with stats */}
                <div className="max-w-6xl mx-auto mb-6">
                    <div className="bg-white rounded-lg shadow-xl p-6">
                        <h1 className="text-3xl font-bold text-center mb-4">
                            {trainerName ? `${trainerName}'s My Pokémon` : 'My Pokémon'}
                        </h1>
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                            <div className="bg-blue-100 p-4 rounded">
                                <p className="text-2xl font-bold text-blue-600">{totalCaught}</p>
                                <p className="text-sm text-gray-600">Total Caught</p>
                            </div>
                            <div className="bg-orange-100 p-4 rounded">
                                <p className="text-2xl font-bold text-orange-600">{totalLegendary}</p>
                                <p className="text-sm text-gray-600">Legendary</p>
                            </div>
                            <div className="bg-purple-100 p-4 rounded">
                                <p className="text-2xl font-bold text-purple-600">{totalMythical}</p>
                                <p className="text-sm text-gray-600">Mythical</p>
                            </div>
                            <div className="bg-green-100 p-4 rounded">
                                <p className="text-2xl font-bold text-green-600">{totalCaught - totalLegendary - totalMythical}</p>
                                <p className="text-sm text-gray-600">Normal</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {/* Search and Filter */}
                <div className="max-w-4xl mx-auto mb-6">
                    <div className="bg-white rounded-lg shadow p-4">
                        <div className="flex flex-col sm:flex-row items-center gap-4">
                            <div className="w-full sm:flex-1">
                                <input 
                                    type="text"
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Search your Pokémon..."
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                />
                            </div>
                            <div className="w-full sm:w-48">
                                <select 
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    value={filterType}
                                    onChange={(e) => setFilterType(e.target.value)}
                                >
                                    {filterTypes.map(type => (
                                        <option key={type.value} value={type.value}>
                                            {type.label}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                {/* Pokemon Grid */}
                <div className="max-w-6xl mx-auto">
                    {filteredPokemon.length === 0 ? (
                        <div className="bg-white rounded-lg shadow p-12 text-center">
                            <div className="text-gray-400 mb-4">
                                <svg className="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p className="text-xl text-gray-600">
                                {userPokemon.length === 0 
                                    ? "No Pokémon caught yet! Visit the Pokémon Center to start your collection."
                                    : "No Pokémon match your search criteria."}
                            </p>
                            {userPokemon.length === 0 && (
                                <Link 
                                    href="/pokemonCenter" 
                                    className="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors"
                                >
                                    Visit Pokémon Center
                                </Link>
                            )}
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            {filteredPokemon.map((pokemon) => (
                                <div 
                                    key={pokemon.id}
                                    className={`${getTypeColor(pokemon)} rounded-lg shadow-lg p-4 text-white relative overflow-hidden transition-transform hover:scale-105`}
                                >
                                    {/* Badges */}
                                    <div className="absolute top-2 right-2 flex flex-col gap-1">
                                        {pokemon.is_legendary && (
                                            <span className="bg-orange-600 text-white px-2 py-1 rounded text-xs font-bold">
                                                Legendary
                                            </span>
                                        )}
                                        {pokemon.is_mythical && (
                                            <span className="bg-purple-600 text-white px-2 py-1 rounded text-xs font-bold">
                                                Mythical
                                            </span>
                                        )}
                                    </div>
                                    
                                    {/* Pokemon Info */}
                                    <h3 className="font-bold text-lg mb-2 pr-16">{pokemon.name}</h3>
                                    
                                    {/* Pokemon Image */}
                                    <div className="flex justify-center items-center h-32 mb-4">
                                        <img 
                                            src={pokemon.image} 
                                            alt={`${pokemon.name} sprite`}
                                            className="max-w-full max-h-full object-contain filter drop-shadow-lg"
                                            onError={(e) => {
                                                (e.target as HTMLImageElement).style.display = 'none';
                                            }}
                                        />
                                    </div>
                                    
                                    {/* Capture Info */}
                                    <div className="text-sm opacity-90">
                                        <p>Caught: {formatDate(pokemon.capture_date)}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </main>
        </Layout>
    );
}