import React from 'react';
import { Head } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface PokedexProps {
    user?: {
        name: string;
        title: string;
        experience: number;
        experienceToNext: number;
        totalPokemon: number;
        mythicalCount: number;
        legendaryCount: number;
        profileImage?: string;
    };
}

export default function Pokedex({ user }: PokedexProps) {
    const defaultUser = {
        name: 'PLAYER_NAME',
        title: 'POKEMASTER',
        experience: 500,
        experienceToNext: 1000,
        totalPokemon: 45,
        mythicalCount: 42,
        legendaryCount: 3,
        profileImage: '/images/profiles/pp.png'
    };

    const userData = user || defaultUser;

    // Calculate progress percentage for rank up (assuming 1500 is max XP needed)
    const totalXPNeeded = 1500;
    const currentXP = userData.experience;
    const progressPercentage = Math.min((currentXP / totalXPNeeded) * 100, 100);

    return (
        <Layout>
            <Head title="Pokédex - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <section className="flex flex-col mt-5 mx-auto max-w-4xl bg-white shadow-xl p-4">
                    <h1 className="text-center text-2xl font-bold mb-4">
                        Hello {userData.name}
                    </h1>
                    <div className="flex flex-col sm:flex-row justify-evenly items-center gap-4">
                        <article className="flex flex-col space-y-2">
                            <h3 className="text-lg font-semibold">Title: <span className="text-blue-600">{userData.title}</span></h3>
                            <h3 className="text-lg">You have gained <span className="text-green-600 font-bold">{userData.experience}XP</span></h3>
                            <h3 className="text-lg">You have <span className="text-purple-600 font-bold">{userData.totalPokemon}</span> Pokémon</h3>
                            <h3 className="text-lg text-red-600">You need <span className="font-bold">{userData.experienceToNext}xp</span> to rank up</h3>
                            
                            {/* XP Progress Bar */}
                            <div className="mt-3">
                                <div className="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>XP Progress</span>
                                    <span>{currentXP}/{totalXPNeeded}</span>
                                </div>
                                <div className="w-full bg-gray-200 rounded-full h-3">
                                    <div 
                                        className="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300"
                                        style={{ width: `${progressPercentage}%` }}
                                    ></div>
                                </div>
                                <div className="text-xs text-gray-500 mt-1 text-center">
                                    {progressPercentage.toFixed(1)}% Complete
                                </div>
                            </div>

                            <div className="mt-4 space-y-1">
                                <h4 className="text-md">Mythical Pokémon: <span className="text-yellow-500 font-bold">{userData.mythicalCount}</span></h4>
                                <h4 className="text-md">Legendary Pokémon: <span className="text-orange-500 font-bold">{userData.legendaryCount}</span></h4>
                            </div>
                        </article>
                        <article className="w-32 sm:w-40">
                            <img 
                                src={userData.profileImage} 
                                alt={`${userData.name} profile`}
                                className="w-full h-auto rounded-lg shadow-lg"
                            />
                        </article>
                    </div>
                </section>
            </main>
        </Layout>
    );
}