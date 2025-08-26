import React from 'react';
import { Head } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface MyPokemonProps {
    user?: {
        name: string;
        title: string;
        experience: number;
        experienceToNext: number;
        mythicalCount: number;
        legendaryCount: number;
        profileImage?: string;
    };
}

export default function MyPokemon({ user }: MyPokemonProps) {
    const defaultUser = {
        name: 'PLAYER_NAME',
        title: 'POKEMASTER',
        experience: 500,
        experienceToNext: 1500,
        mythicalCount: 42,
        legendaryCount: 3,
        profileImage: '/images/profiles/pp.png'
    };

    const userData = user || defaultUser;

    return (
        <Layout>
            <Head title="My Pokémon - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <section className="flex flex-col mt-5 mx-auto max-w-4xl bg-white shadow-xl p-4">
                    <h1 className="text-center text-2xl font-bold mb-4">
                        Hello {userData.name}
                    </h1>
                    <div className="flex flex-col sm:flex-row justify-evenly items-center gap-4">
                        <article className="flex flex-col">
                            <h3>Title: {userData.title}</h3>
                            <h3>You Have Gain {userData.experience}PX</h3>
                            <h3>You need {userData.experienceToNext}xp To Rank UP</h3>
                            <h3>You have {userData.mythicalCount} Mythical Pokémon</h3>
                            <h3>You have {userData.legendaryCount} Legendary Pokémon</h3>
                        </article>
                        <article className="w-25 bg-green">
                            <img 
                                src={userData.profileImage} 
                                alt={`${userData.name} profile`}
                                className="w-full h-auto rounded"
                            />
                        </article>
                    </div>
                </section>
            </main>
        </Layout>
    );
}