<x-layout>
    <main class="bg-gray-200 min-h-screen">
        <section class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-center mb-8">Pokemon Center</h1>

            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center">
                    {{ $error }}
                </div>
            @endif

            @if(!empty($pokemons))
                <article class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($pokemons as $index => $pokemon)
                        <div class="transform transition-all duration-300 hover:-translate-y-2">
                            <div class="bg-black rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer h-48 flex items-center justify-center overflow-hidden" onclick="revealPokemon(this)">
                                <div class="flex flex-col items-center justify-center p-4">
                                    <div class="w-28 h-28 flex items-center justify-center relative">
                                        <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $index + 1 }}.png"
                                            alt="{{ ucfirst($pokemon) }}"
                                            class="w-full h-full object-contain transition-all duration-300 brightness-0">
                                    </div>
                                    <h5 class="mt-3 text-lg font-semibold capitalize text-black transition-all duration-300">
                                        {{ ucfirst($pokemon) }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </article>
            @else
                <div class="text-center text-gray-600">
                    <p>No Pokémon available at the moment. Please try again later.</p>
                </div>
            @endif
        </section>
    </main>

    <script>
        function revealPokemon(card) {
            // Change background to white
            card.classList.remove('bg-black');
            card.classList.add('bg-gradient-to-b', 'from-white', 'to-gray-50');

            // Reveal the Pokemon
            const img = card.querySelector('img');
            img.classList.remove('brightness-0');

            // Show the name
            const name = card.querySelector('h5');
            name.classList.remove('text-black');
            name.classList.add('text-gray-800');

            // Remove click handler
            card.onclick = null;
        }
    </script>
</x-layout>
