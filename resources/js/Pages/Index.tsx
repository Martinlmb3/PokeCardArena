import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Components/Layout';

export default function Index() {
    return (
        <Layout>
            <Head title="PokéCard Arena - Home" />
            <main className="bg-gray-200 p-5">
                <section className="flex flex-col items-center justify-center m-auto bg-gray-200 h-screen text-center">
                    <div className="mb-20 max-w-6xl mx-auto">
                        <article className="text-white mb-12 px-6">
                            <h1 className="text-6xl md:text-8xl font-extrabold mb-6 leading-tight">
                                <span className="text-red-600 drop-shadow-lg">PokéCard Arena</span><br /></h1>
                                <span className="md:text-7xl text-gray-100">The place Where You Catch 'Em All, Daily!</span>

                        </article>
                        <article className="mb-16 px-8">
                            <h3 className="text-3xl md:text-4xl text-gray-700 leading-relaxed font-medium">
                                Yup, you read that right! Log in daily for your chance to loot awesome Pokemon cards.<br />
                                Build your dream deck without spending a dime. Let the daily looting begin!
                            </h3>
                        </article>
                    </div>
                    <article className="flex justify-center gap-6">
                        <Link href="/signup">
                            <button className="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-lg transition duration-300 ease-in-out transform hover:scale-105" style={{ borderRadius: '0.5rem' }}>
                                Sign Up
                            </button>
                        </Link>
                        <Link href="/login">
                            <button className="bg-gray-800 hover:bg-gray-900 text-white font-bold py-4 px-8 rounded-lg transition duration-300 ease-in-out transform hover:scale-105" style={{ borderRadius: '0.5rem' }}>
                                Login
                            </button>
                        </Link>
                    </article>
                </section>
            </main>
        </Layout>
    );
}
