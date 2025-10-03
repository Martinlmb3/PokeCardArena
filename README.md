<div align="center">
  <img src="public/images/logos/pokécard-logo.png" width="400" alt="PokéCard Arena Logo">
  
  # PokéCard Arena
  
  **The Ultimate Digital Pokémon Card Collection Experience**
  
  <p>
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
    <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version">
    <img src="https://img.shields.io/badge/React-18+-61DAFB?style=for-the-badge&logo=react&logoColor=black" alt="React Version">
    <img src="https://img.shields.io/badge/TypeScript-5+-3178C6?style=for-the-badge&logo=typescript&logoColor=white" alt="TypeScript Version">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind Version">
    <img src="https://img.shields.io/badge/Inertia.js-1.x-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia Version">
  </p>
  
  <p>
    <img src="https://img.shields.io/badge/PostgreSQL-336791?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL Database">
    <img src="https://img.shields.io/badge/Playwright-2EAD33?style=for-the-badge&logo=playwright&logoColor=white" alt="Playwright Testing">
    <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker Support">
    <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
  </p>

  [🚀 Live Demo](https://your-render-app.onrender.com) • [📖 Documentation](#installation) • [🐛 Report Bug](https://github.com/martinlmb3/PokeCardArena/issues) • [💡 Request Feature](https://github.com/martinlmb3/PokeCardArena/issues)

</div>

---

## 🎮 About PokéCard Arena

PokéCard Arena is a modern web application that brings the excitement of Pokémon card collecting to the digital world. Built with Laravel 11, React, and TypeScript, it offers trainers an immersive experience to collect, trade, and battle with digital Pokémon cards.

### ✨ Key Features

<table>
<tr>
<td width="50%">

**🎁 Daily Rewards System**
- Collect new cards every day
- Streak bonuses for consecutive logins
- Special event cards

**🎴 Collection Management**
- Advanced filtering and sorting
- Card rarity tracking
- Collection statistics

**🔄 Trading System**
- Real-time trading with other trainers
- Secure transaction system
- Trade history tracking

</td>
<td width="50%">

**🏆 Achievement System**
- Unlock badges and titles
- Leaderboards and rankings
- Progress tracking

**👤 Trainer Profiles**
- Customizable avatars
- Showcase favorite cards
- Activity feed

**⚔️ Battle Arena**
- Turn-based card battles
- ELO rating system
- Tournament modes

</td>
</tr>
</table>

## 🛠️ Tech Stack

- **Backend**: Laravel 11 with PHP 8.2+
- **Frontend**: React 18 with TypeScript
- **Styling**: Tailwind CSS 3.x
- **Database**: PostgreSQL (production) / SQLite (development)
- **Full-stack**: Inertia.js for seamless SPA experience
- **Bundling**: Vite for fast development and optimized builds
- **Testing**: Playwright for end-to-end testing
- **Deployment**: Docker with multi-stage builds

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Node.js 18 or higher
- Composer
- Git

### Local Development

```bash
# 1. Clone the repository
git clone https://github.com/martinlmb3/PokeCardArena.git
cd PokeCardArena

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Set up database
php artisan migrate --seed

# 6. Build frontend assets
npm run dev

# 7. Start the development server
php artisan serve
```

Visit `http://localhost:8000` to see the application running!

### 🐳 Docker Development

```bash
# Build and run with Docker
docker build -t pokecard-arena .
docker run -p 8000:80 -e APP_KEY="your-app-key" pokecard-arena
```

## 📁 Project Structure

```
PokeCardArena/
├── app/                    # Laravel application logic
│   ├── Http/Controllers/   # API endpoints and page controllers
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic services
├── resources/
│   ├── js/                # React TypeScript components
│   │   ├── Components/    # Reusable UI components
│   │   ├── Pages/         # Inertia.js pages
│   │   └── Types/         # TypeScript type definitions
│   └── views/             # Blade templates
├── database/
│   ├── migrations/        # Database schema
│   └── seeders/          # Sample data
├── public/               # Static assets
└── docker/              # Docker configuration
```

## 🌐 Deployment

### Deploy to Render

1. **Prepare your repository**:
   ```bash
   git add .
   git commit -m "Ready for deployment"
   git push origin main
   ```

2. **Create services on Render**:
   - Create a new Web Service
   - Connect your GitHub repository
   - Set environment to "Docker"
   - Add environment variables:
     ```
     APP_ENV=production
     APP_DEBUG=false
     APP_KEY=base64:your-generated-key
     DB_CONNECTION=pgsql  # or sqlite
     ```

3. **Optional: Add PostgreSQL database**:
   - Create a PostgreSQL service
   - Copy the DATABASE_URL to your web service environment

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_ENV` | Application environment | `local` |
| `APP_DEBUG` | Debug mode | `true` |
| `APP_KEY` | Application encryption key | Generated |
| `DB_CONNECTION` | Database driver | `sqlite` |
| `QUEUE_CONNECTION` | Queue driver | `database` |

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run JavaScript tests
npm run test
```

## 📚 API Documentation

The application provides a RESTful API for card management:

- `GET /api/cards` - List all cards
- `POST /api/cards/daily` - Claim daily card
- `GET /api/collection` - User's collection
- `POST /api/trades` - Create trade offer

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards for PHP
- Use ESLint and Prettier for JavaScript/TypeScript
- Write tests for new features
- Update documentation as needed

## 🐛 Known Issues & Roadmap

- [ ] Mobile responsiveness improvements
- [ ] Real-time notifications
- [ ] Advanced trading filters
- [ ] Card animation effects
- [ ] Multi-language support

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Pokémon Data**: [PokéAPI](https://pokeapi.co/) for comprehensive Pokémon information
- **Framework**: [Laravel](https://laravel.com) for the robust backend foundation
- **Frontend**: [React](https://reactjs.org) and [Inertia.js](https://inertiajs.com) for seamless user experience
- **Icons**: [Heroicons](https://heroicons.com) for beautiful UI icons
- **Legal**: Pokémon © 1995-2025 Nintendo/Creatures Inc./GAME FREAK inc.

## 📞 Support

- 📧 Email: [martinlmb3@gmail.com](mailto:martinlmb3@gmail.com)
- 🐛 Issues: [GitHub Issues](https://github.com/martinlmb3/PokeCardArena/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/martinlmb3/PokeCardArena/discussions)

---

<div align="center">
  Made with ❤️ by <a href="https://github.com/martinlmb3">martinlmb3</a>
</div>
