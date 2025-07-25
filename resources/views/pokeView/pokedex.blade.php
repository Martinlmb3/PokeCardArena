<x-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <main class="bg-gray-200 p-5">
        <section class="flex flex-col mt-5 mx-auto max-w-4xl bg-white shadow-xl p-4">
            <h1 class="text-center text-2xl font-bold mb-4">Hello {{ $user->name }}</h1>
            <div class="flex flex-col sm:flex-row justify-evenly items-center gap-4">
            <article class="flex flex-col">
                <h3>Title: {{ $user->title }}</h3>
                <h3>You Have 0PX</h3>
                <h3>You need 1500xp To Rank UP</h3>
                <h3>You have 42 Mythical Pokémon</h3>
                <h3>You have 3 Legendary Pokémon</h3>
            </article>
            <article class="w-25 bg-green">
                <img src="{{ asset(str_replace('/public', '', $user->profile)) }}" alt="Profile Picture">
            </article>
            </div>
        </section>
    </main>
</x-layout>
