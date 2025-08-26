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
                                <div className="shrink-0">
                                    <Link href="/" className="hover:opacity-75 transition-opacity duration-150">
                                        <img className="size-15" src="/images/logos/pokécard-logo.png" alt="pokécard-logo" />
                                    </Link>
                                </div>
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
                            </div>
                            <div className="hidden md:block">
                                <div className="ml-4 flex items-center md:ml-6">
                                    {auth?.user ? (
                                        <div className="relative ml-3">
                                            <div>
                                                <button 
                                                    type="button" 
                                                    className="relative flex max-w-xs items-center rounded-full bg-red-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden" 
                                                    id="user-menu-button" 
                                                    aria-expanded="false" 
                                                    aria-haspopup="true"
                                                >
                                                    <span className="absolute -inset-1.5"></span>
                                                    <span className="sr-only">Open user menu</span>
                                                    <img className="size-8 rounded-full" src="/images/profiles/pp.png" alt="" />
                                                </button>
                                            </div>
                                            <div className="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabIndex={-1}>
                                                <Link 
                                                    href="/profile" 
                                                    className="block px-4 py-2 text-sm text-gray-700 no-underline" 
                                                    role="menuitem" 
                                                    tabIndex={-1} 
                                                    id="user-menu-item-0"
                                                >
                                                    Your Profile
                                                </Link>
                                                <form onSubmit={handleLogout}>
                                                    <button 
                                                        type="submit"
                                                        className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                                        role="menuitem" 
                                                        tabIndex={-1} 
                                                        id="user-menu-item-2"
                                                    >
                                                        Sign out
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
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
                            <form onSubmit={handleLogout}>
                                <button 
                                    type="submit"
                                    className="block w-full text-left rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white"
                                >
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>
            </header>
            {children}
            <footer className="block bottom-0 w-full bg-white" style={{ color: '#6c757d' }}>
                <div className="max-w-7xl mx-auto px-4 py-2">
                    <ul className="flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-8 border-b pb-2">
                        <li className="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">Home</li>
                        <li className="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">Pay To Win</li>
                        <li className="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">FAQs</li>
                        <li className="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">About</li>
                    </ul>
                    <p className="text-center text-muted my-2 text-xs sm:text-sm">©PokéCard Arena 2025</p>
                </div>
            </footer>
        </div>
    );
}