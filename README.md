<p align="center"><img src="public/images/logos/pokécard-logo.png" width="400" alt="PokéCard Logo"></p>

<p align="center">
<img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
<img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version">
<img src="https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind Version">
<img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite Database">
<img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

## About PokéCard

PokéCard is a web application that allows Pokémon trainers to collect digital Pokémon cards daily. Built with Laravel, it offers an engaging experience with features such as:

- Daily card looting system
- Personal card collection management
- Card trading between trainers
- Experience points (XP) system
- Trainer profile customization
- Rare card hunting achievements

## Features

- **Daily Rewards**: Get new Pokémon cards every day
- **Collection Management**: Organize, view, and manage your card collection
- **Trading System**: Exchange cards with other trainers
- **Achievement System**: Earn badges and titles through various accomplishments
- **Trainer Profile**: Customize your trainer profile and showcase your best cards
- **Card Rarity System**: Common, Uncommon, Rare, and Legendary cards to collect

## Installation

```bash
# Clone the repository
git clone https://github.com/martinlmb3/pokecard.git

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Start the development server
php artisan serve
```

## Contributing

We welcome contributions to the PokéCard project! Fell free to clone or fork the project if u want to add some news stuff on it.

## Security

If you discover any security vulnerabilities within PokéCard, please send me a message [Martinlmb3](https://github.com/Martinlmb3).

## License

PokéCard is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

- Pokémon cards data provided by [PokéAPI](https://pokeapi.co/)
- Built with [Laravel](https://laravel.com)
- Card images © Nintendo
