import React, { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface Particle {
    id: number;
    x: number;
    y: number;
    size: number;
    speedX: number;
    speedY: number;
    opacity: number;
}

interface Pokeball {
    id: number;
    x: number;
    y: number;
    size: number;
    speedX: number;
    speedY: number;
    rotation: number;
    rotationSpeed: number;
}

export default function Index() {
    const [particles, setParticles] = useState<Particle[]>([]);
    const [pokeballs, setPokeballs] = useState<Pokeball[]>([]);

    useEffect(() => {
        const generateParticles = () => {
            const newParticles: Particle[] = [];
            for (let i = 0; i < 30; i++) {
                newParticles.push({
                    id: i,
                    x: Math.random() * window.innerWidth,
                    y: Math.random() * window.innerHeight,
                    size: Math.random() * 4 + 2,
                    speedX: (Math.random() - 0.5) * 0.5,
                    speedY: (Math.random() - 0.5) * 0.5,
                    opacity: Math.random() * 0.8 + 0.2,
                });
            }
            setParticles(newParticles);
        };

        const generatePokeballs = () => {
            const newPokeballs: Pokeball[] = [];
            for (let i = 0; i < 5; i++) {
                newPokeballs.push({
                    id: i,
                    x: Math.random() * window.innerWidth,
                    y: Math.random() * window.innerHeight,
                    size: Math.random() * 30 + 20,
                    speedX: (Math.random() - 0.5) * 0.3,
                    speedY: (Math.random() - 0.5) * 0.3,
                    rotation: 0,
                    rotationSpeed: (Math.random() - 0.5) * 2,
                });
            }
            setPokeballs(newPokeballs);
        };

        generateParticles();
        generatePokeballs();

        const animateParticles = () => {
            setParticles(prev => prev.map(particle => {
                const newX = particle.x + particle.speedX;
                const newY = particle.y + particle.speedY;
                return {
                    ...particle,
                    x: newX > window.innerWidth ? 0 : newX < 0 ? window.innerWidth : newX,
                    y: newY > window.innerHeight ? 0 : newY < 0 ? window.innerHeight : newY,
                };
            }));

            setPokeballs(prev => prev.map(pokeball => {
                const newX = pokeball.x + pokeball.speedX;
                const newY = pokeball.y + pokeball.speedY;
                return {
                    ...pokeball,
                    x: newX > window.innerWidth + pokeball.size ? -pokeball.size :
                       newX < -pokeball.size ? window.innerWidth + pokeball.size : newX,
                    y: newY > window.innerHeight + pokeball.size ? -pokeball.size :
                       newY < -pokeball.size ? window.innerHeight + pokeball.size : newY,
                    rotation: pokeball.rotation + pokeball.rotationSpeed,
                };
            }));
        };

        const interval = setInterval(animateParticles, 50);
        return () => clearInterval(interval);
    }, []);

    return (
        <Layout>
            <Head title="PokéCard Arena - Home" />
            <main className="relative bg-gradient-to-br from-blue-900 via-purple-800 to-red-900 p-5 overflow-hidden">
                {/* Animated Background Particles */}
                <div className="absolute inset-0 pointer-events-none">
                    {particles.map(particle => (
                        <div
                            key={particle.id}
                            className="absolute bg-yellow-300 rounded-full animate-pulse"
                            style={{
                                left: `${particle.x}px`,
                                top: `${particle.y}px`,
                                width: `${particle.size}px`,
                                height: `${particle.size}px`,
                                opacity: particle.opacity,
                                boxShadow: '0 0 10px rgba(255, 255, 0, 0.5)',
                            }}
                        />
                    ))}
                </div>

                {/* Floating Pokeballs */}
                <div className="absolute inset-0 pointer-events-none">
                    {pokeballs.map(pokeball => (
                        <div
                            key={pokeball.id}
                            className="absolute opacity-20"
                            style={{
                                left: `${pokeball.x}px`,
                                top: `${pokeball.y}px`,
                                width: `${pokeball.size}px`,
                                height: `${pokeball.size}px`,
                                transform: `rotate(${pokeball.rotation}deg)`,
                            }}
                        >
                            <div className="w-full h-full relative">
                                <div className="w-full h-1/2 bg-red-500 rounded-t-full border-2 border-black"></div>
                                <div className="w-full h-1/2 bg-white rounded-b-full border-2 border-black border-t-0"></div>
                                <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/4 h-1/4 bg-white rounded-full border-2 border-black">
                                    <div className="w-1/2 h-1/2 bg-gray-300 rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                <section className="relative flex flex-col items-center justify-center m-auto h-screen text-center z-10">
                    <div className="mb-20 max-w-6xl mx-auto">
                        <article className="text-white mb-12 px-6">
                            <h1 className="text-6xl md:text-8xl font-extrabold mb-6 leading-tight transform hover:scale-105 transition-all duration-300 cursor-default">
                                <span className="text-red-400 drop-shadow-lg bg-gradient-to-r from-red-400 to-yellow-400 bg-clip-text text-transparent animate-pulse">PokéCard Arena</span><br /></h1>
                                <span className="md:text-7xl text-white drop-shadow-lg hover:text-yellow-200 transition-colors duration-300">The place Where You Catch 'Em All, Daily!</span>

                        </article>
                        <article className="mb-16 px-8">
                            <h3 className="text-3xl md:text-4xl text-white leading-relaxed font-medium drop-shadow-md hover:text-yellow-100 transition-colors duration-300">
                                Yup, you read that right! Log in daily for your chance to loot awesome Pokemon cards.<br />
                                Build your dream deck without spending a dime. Let the daily looting begin!
                            </h3>
                        </article>
                    </div>
                    <article className="flex justify-center gap-6">
                        <Link href="/signup">
                            <button className="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 px-8 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/25" style={{ borderRadius: '0.5rem' }}>
                                Sign Up
                            </button>
                        </Link>
                        <Link href="/login">
                            <button className="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold py-4 px-8 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg hover:shadow-yellow-500/25" style={{ borderRadius: '0.5rem' }}>
                                Login
                            </button>
                        </Link>
                    </article>
                </section>
            </main>
        </Layout>
    );
}
