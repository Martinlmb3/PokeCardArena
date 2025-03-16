<x-layout>
    <main class="bg-gray-200 min-h-screen">
        <section class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-center mb-5">Pokemon Center</h1>

            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center">
                    {{ $error }}
                </div>
            @endif

            @if(!empty($randomPokemons))
                <article class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($randomPokemons as $pokemon)
                        <div class="transform transition-all duration-300 hover:-translate-y-2">
                            <a href="{{ route('pokemonCenter', ['id' => Auth::id()]) }}?pokemon_id={{ $pokemon['id'] }}" class="block">
                                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer h-48 flex items-center justify-center overflow-hidden">
                                    <div class="flex flex-col items-center justify-center p-4">
                                        <div class="w-28 h-28 flex items-center justify-center relative">
                                            <img src="{{ $pokemon['image'] }}"
                                                alt="{{ ucfirst($pokemon['name']) }}"
                                                class="w-full h-full object-contain transition-all duration-300">
                                        </div>
                                        <h5 class="mt-3 text-lg font-semibold capitalize text-gray-800 transition-all duration-300">
                                            {{ ucfirst($pokemon['name']) }}
                                        </h5>
                                        <div class="flex flex-wrap gap-1 mt-1 justify-center">
                                            @foreach($pokemon['types'] as $type)
                                                <span class="px-2 py-0.5 bg-gray-200 rounded-full text-xs capitalize">{{ $type }}</span>
                                            @endforeach
                                        </div>
                                        @if(isset($pokemon['is_legendary']) && $pokemon['is_legendary'])
                                            <span class="mt-1 px-2 py-0.5 bg-yellow-200 text-yellow-800 rounded-full text-xs">Legendary</span>
                                        @endif
                                        @if(isset($pokemon['is_mythical']) && $pokemon['is_mythical'])
                                            <span class="mt-1 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full text-xs">Mythical</span>
                                        @endif
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
