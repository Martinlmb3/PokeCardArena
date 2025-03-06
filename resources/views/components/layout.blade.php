<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body >
<header class="min-h-full">
    <nav class="bg-red-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
            <div class="shrink-0">
            <a href="/" class="hover:opacity-75 transition-opacity duration-150">
                <img class="size-15" src="{{ asset('images/logos/pokécard-logo.png') }}" alt="pokécard-logo">
            </a>
            </div>
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    @auth
                        <a href="{{ route('pokedex') }}" class="rounded-md bg-red-900 px-3 py-2 text-sm font-medium text-white" aria-current="page">Pokédex</a>
                        <a href="{{ route('pokemonCenter') }}" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-red-900 text-white no-underline">Pokémon Center</a>
                        <a href="{{ route('myPokemon') }}" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-red-900 text-white no-underline">My Pokémon</a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
            <!-- Profile dropdown -->
            <div class="relative ml-3">
                @auth
                    <form method="POST" action="{{ route('profile') }}">
                        <button type="button" class="relative flex max-w-xs items-center rounded-full bg-red-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img class="size-8 rounded-full" src="{{ asset('images/profiles/pp.png') }}" alt="">
                        </button>
                    </form>
                @endauth
                <!--
                Dropdown menu, show/hide based on menu state.

                Entering: "transition ease-out duration-100"
                    From: "transform opacity-0 scale-95"
                    To: "transform opacity-100 scale-100"
                Leaving: "transition ease-in duration-75"
                    From: "transform opacity-100 scale-100"
                    To: "transform opacity-0 scale-95"
                -->
                <!-- Active: "bg-gray-100 outline-hidden", Not Active: "" -->
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block px-4 py-2 text-sm text-gray-700 no-underline" role="menuitem" tabindex="-1" id="user-menu-item-1">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
        </div>
    </div>
    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
            @auth
                <a href="pokeView/pokedex" class="block rounded-md bg-red-900 px-3 py-2 text-base font-medium text-white" aria-current="page">Pokédex</a>
                <a href="pokeView/myPokemon" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white no-underline ">My Pokémon</a>
                <a href="pokeView/pokemonCenter" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white">Pokémon Center</a>
                <a href="pokeView/logout" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white">Log Out</a>
            @endauth
            </div>
        </div>
    </nav>
    </header>
    {{ $slot }}
    </div>
    <footer class="block bottom-0 w-full bg-white" style="color: #6c757d;">
        <div class="max-w-7xl mx-auto px-4 py-2"> <!-- Added padding -->
            <ul class="flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-8 border-b pb-2">
                <!-- Added more responsive spacing and improved hover states -->
                <li class="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">Home</li>
                <li class="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">Pay To Win</li>
                <li class="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">FAQs</li>
                <li class="nav-link text-muted hover:text-red-900 cursor-pointer transition-colors duration-200">About</li>
            </ul>
            <p class="text-center text-muted my-2 text-xs sm:text-sm">©PokéCard Arena 2025</p>
        </div>
    </footer>
</body>
</html>
