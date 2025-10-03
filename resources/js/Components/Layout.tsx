import React, { ReactNode } from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';

interface LayoutProps {
    children: ReactNode;
}

export default function Layout({ children }: LayoutProps) {
    const { auth } = usePage<{ auth: { user: any } }>().props;
    const { post } = useForm();

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        post('/logout');
    };

    return (
        <div>
            <header className="min-h-full">
                <nav className="bg-red-800">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="flex h-16 items-center justify-between">
                            <div className="flex items-center">
                                <div className="flex-shrink-0">
                                    <Link href="/" className="hover:opacity-75 transition-opacity duration-150">
                                        <img className="h-12 w-12 object-contain" src="/images/logos/pokécard-logo.png" alt="pokécard-logo"/>
                                    </Link>
                                </div>
                                {auth?.user && (
                                    <div className="hidden md:block">
                                        <div className="ml-10 flex items-baseline space-x-4">
                                            <Link
                                                href="/pokedex"
                                                className="rounded-md bg-red-900 px-3 py-2 text-sm font-medium text-white"
                                                aria-current="page"
                                            >
                                                Pokédex
                                            </Link>
                                            <Link
                                                href="/pokemonCenter"
                                                className="rounded-md px-3 py-2 text-sm font-medium hover:bg-red-900 text-white no-underline"
                                            >
                                                Pokémon Center
                                            </Link>
                                            <Link
                                                href="/myPokemon"
                                                className="rounded-md px-3 py-2 text-sm font-medium hover:bg-red-900 text-white no-underline"
                                            >
                                                My Pokémon
                                            </Link>
                                        </div>
                                    </div>
                                )}
                            </div>
                            <div className="hidden md:block">
                                <div className="ml-4 flex items-center md:ml-6 space-x-4">
                                    {auth?.user ? (
                                        <>
                                            <Link
                                                href="/profile"
                                                className="relative flex max-w-xs items-center rounded-full bg-red-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden hover:bg-red-700 transition-colors"
                                            >
                                                <span className="absolute -inset-1.5"></span>
                                                <span className="sr-only">Your profile</span>
                                                <img className="size-8 rounded-full" src={auth?.user?.profile || "/images/profiles/pp.png"} alt="" />
                                            </Link>
                                            <form onSubmit={handleLogout}>
                                                <button
                                                    type="submit"
                                                    className="rounded-md px-3 py-2 text-sm font-medium text-white hover:bg-red-900 transition-colors"
                                                >
                                                    Logout
                                                </button>
                                            </form>
                                        </>

                                    ) : (
                                        <div className="flex space-x-4">
                                            <Link
                                                href="/login"
                                                className="rounded-md px-3 py-2 text-sm font-medium text-white hover:bg-red-900"
                                            >
                                                Login
                                            </Link>
                                            <Link
                                                href="/signup"
                                                className="rounded-md bg-red-900 px-3 py-2 text-sm font-medium text-white hover:bg-red-800"
                                            >
                                                Sign Up
                                            </Link>
                                        </div>
                                    )}
                                </div>
                            </div>
                            <div className="-mr-2 flex md:hidden">
                                <button
                                    type="button"
                                    className="relative inline-flex items-center justify-center rounded-md bg-red-900 p-2 text-gray-400 hover:bg-red-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden"
                                    aria-controls="mobile-menu"
                                    aria-expanded="false"
                                >
                                    <span className="absolute -inset-0.5"></span>
                                    <span className="sr-only">Open main menu</span>
                                    <svg className="block size-6" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    </svg>
                                    <svg className="hidden size-6" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    {auth?.user && (
                        <div className="md:hidden" id="mobile-menu">
                            <div className="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                                <Link
                                    href="/pokedex"
                                    className="block rounded-md bg-red-900 px-3 py-2 text-base font-medium text-white"
                                    aria-current="page"
                                >
                                    Pokédex
                                </Link>
                                <Link
                                    href="/myPokemon"
                                    className="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white no-underline"
                                >
                                    My Pokémon
                                </Link>
                                <Link
                                    href="/pokemonCenter"
                                    className="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white"
                                >
                                    Pokémon Center
                                </Link>
                                <Link
                                    href="/profile"
                                    className="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white"
                                >
                                    Your Profile
                                </Link>
                                <form onSubmit={handleLogout}>
                                    <button
                                        type="submit"
                                        className="block w-full text-left rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white"
                                    >
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    )}
                </nav>
            </header>
            {children}
            <footer className="bg-red-900 text-white w-full">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                        {/* Brand Section */}
                        <div className="md:col-span-2">
                            <Link href="/" className="hover:opacity-75 transition-opacity duration-150">
                                <h3 className="text-3xl font-bold mb-4 text-white">PokéCard Arena</h3>
                            </Link>
                            <p className="text-red-100 mb-6 max-w-md leading-relaxed">
                                The place where you catch 'em all, daily! Build your dream Pokemon deck without spending a dime through daily looting adventures.
                            </p>
                        </div>

                        {/* Quick Links */}
                        <div>
                            <h4 className="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                            <ul className="space-y-3">
                                <li>
                                    <Link href="/" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        Home
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/pokedex" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        Pokédex
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/pokemonCenter" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        Pokémon Center
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/myPokemon" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        My Pokémon
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        {/* Support */}
                        <div>
                            <h4 className="text-lg font-semibold mb-4 text-white">Support</h4>
                            <ul className="space-y-3">
                                <li>
                                    <Link href="/help" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        Help Center
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/contact" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        Contact Us
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/privacy" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        Privacy Policy
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/terms" className="text-red-100 hover:text-white transition-colors text-sm block py-1">
                                        Terms of Service
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {/* Bottom Section */}
                    <div className="border-t border-red-800 mt-8 pt-8">
                        <div className="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <p className="text-red-100 text-sm">
                                © {new Date().getFullYear()} PokéCard Arena. All rights reserved.
                            </p>
                            <p className="text-red-100 text-sm">
                                Made with ❤️ for Pokemon trainers everywhere
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
