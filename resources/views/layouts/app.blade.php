<!doctype html>
<html>
<head>
<style>
    *{margin: 0; box-sizing: border-box;}
</style>
    <meta charset="UTF-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
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
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="" class="rounded-md bg-red-900 px-3 py-2 text-sm font-medium text-white" aria-current="page">Pokédex</a>
                <a href="" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-red-900 text-white no-underline">Pokémon Center</a>
                <a href="" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-red-900 text-white no-underline">My Pokémon</a>
            </div>
            </div>
        </div>
        <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
            <!-- Profile dropdown -->
            <div class="relative ml-3">
                <div>
                <button type="button" class="relative flex max-w-xs items-center rounded-full bg-red-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="absolute -inset-1.5"></span>
                    <span class="sr-only">Open user menu</span>
<!--Photo du User--> <img class="size-8 rounded-full" src="{{ asset('images/profiles/pp.png') }}" alt="">
                </button>
                </div>
                <!--
                Dropdown menu, show/hide based on menu state.

                Entering: "transition ease-out duration-100"
                    From: "transform opacity-0 scale-95"
                    To: "transform opacity-100 scale-100"
                Leaving: "transition ease-in duration-75"
                    From: "transform opacity-100 scale-100"
                    To: "transform opacity-0 scale-95"
                -->
                <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                <!-- Active: "bg-gray-100 outline-hidden", Not Active: "" -->
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 no-underline" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 no-underline" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
                </div>
            </div>
            </div>
        </div>
        <div class="-mr-2 flex md:hidden">
            <!-- Mobile menu button -->
            <button type="button" class="relative inline-flex items-center justify-center rounded-md bg-red-900 p-2 text-gray-400 hover:bg-red-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden" aria-controls="mobile-menu" aria-expanded="false">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>
            <!-- Menu open: "hidden", Menu closed: "block" -->
            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <!-- Menu open: "block", Menu closed: "hidden" -->
            <svg class="hidden size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
            </button>
        </div>
        </div>
    </div>
    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
        <a href="pokeView/pokedex" class="block rounded-md bg-red-900 px-3 py-2 text-base font-medium text-white" aria-current="page">Pokédex</a>
        <a href="pokeView/myPokemon" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white no-underline ">My Pokémon</a>
        <a href="pokeView/pokemonCenter" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white">Pokémon Center</a>
        <a href="pokeView/logout" class="block rounded-md px-3 py-2 text-base font-medium hover:bg-red-900 text-white">Log Out</a>
        </div>
        </div>
    </nav>
    </header>
    {{$slot}}
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
