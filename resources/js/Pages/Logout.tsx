import React, { useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import Layout from '../Components/Layout';

export default function Logout() {
    useEffect(() => {
        const timer = setTimeout(() => {
            router.post('/logout');
        }, 2000);

        return () => clearTimeout(timer);
    }, []);

    return (
        <Layout>
            <Head title="Logging Out - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <section className="flex flex-col mt-5 mx-auto max-w-4xl bg-white shadow-xl p-4 text-center">
                    <h1 className="text-2xl font-bold mb-4">Logging Out...</h1>
                    <p className="text-lg">Thank you for playing PokéCard Arena!</p>
                    <p className="text-sm text-gray-600 mt-2">Redirecting you to the home page...</p>
                    <div className="mt-4">
                        <div className="spinner-border text-primary" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    </div>
                </section>
            </main>
        </Layout>
    );
}