import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Components/Layout';

export default function Index() {
    return (
        <Layout>
            <Head title="PokéCard Arena - Home" />
            <main className="bg-gray-200 p-5">
                <section className="flex flex-column m-auto bg-gray-200 h-screen text-center pt-80">
                    <article className="text-white mb-3">
                        <h1>
                            <span className="text-red-600">PokéCard Arena</span> The place Where You Catch 'Em All, Daily!
                        </h1>
                    </article>
                    <article>
                        <h3>
                            Yup, you read that right! Log in daily for your chance to loot awesome Pokemon cards.<br />
                            Build your dream deck without spending a dime. Let the daily looting begin!
                        </h3>
                    </article>
                    <article className="p-4 flex justify-center gap-4">
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