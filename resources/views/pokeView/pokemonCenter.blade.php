<x-layout>
    <main class="bg-gray-200 min-h-screen">
        <section class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-center mb-5">Pokemon Center</h1>

            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center">
                    {{ $error }}
                </div>
            @endif

            @if(!empty($pokemons))
                <article class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($pokemons as $index => $pokemon)
                        <div class="transform transition-all duration-300 hover:-translate-y-2">
                            <a href="{{ route('pokemonCenter', ['id' => Auth::id()]) }}?pokemon_id={{ $index + 1 }}" class="block">
                                <div class="bg-black rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer h-48 flex items-center justify-center overflow-hidden" onclick="revealPokemon(this, event)">
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
                            </a>
                        </div>
                    @endforeach
                </article>
            @else
                <div class="text-center text-gray-600">
                    <p>No Pokémon available at the moment. Please try again later.</p>
                </div>
            @endif
            
            @if(isset($selectedPokemon))
                <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4 capitalize">{{ $selectedPokemon['name'] }}</h2>
                    
                    <div class="flex flex-col md:flex-row">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0 flex justify-center">
                            <img src="{{ $selectedPokemon['image'] }}" alt="{{ $selectedPokemon['name'] }}" class="w-48 h-48 object-contain">
                        </div>
                        
                        <div class="w-full md:w-2/3">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold">Types</h3>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($selectedPokemon['types'] as $type)
                                        <span class="px-3 py-1 bg-gray-200 rounded-full text-sm capitalize">{{ $type }}</span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold">Height</h3>
                                    <p>{{ $selectedPokemon['height'] / 10 }} m</p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Weight</h3>
                                    <p>{{ $selectedPokemon['weight'] / 10 }} kg</p>
                                </div>
                            </div>
                            
                            @if(isset($selectedPokemon['description']))
                                <div>
                                    <h3 class="text-lg font-semibold">Description</h3>
                                    <p class="mt-1">{{ $selectedPokemon['description'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </main>

    <script>
        function revealPokemon(card, event) {
            // Prevent default navigation
            event.preventDefault();
            
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
            
            // Navigate to the page after a small delay
            setTimeout(() => {
                window.location = card.parentElement.href;
            }, 500);
        }
    </script>
</x-layout>
