
# 🚀 Starter Kit for Laravel

A modern, feature-rich starter kit for Laravel applications that combines the power of Laravel with Vue 3, Nuxt UI, and TailwindCSS. This starter kit provides a solid foundation for building scalable web applications with modern development practices.

## 🎯 About Project

This starter kit is designed to jumpstart your Laravel development with a carefully curated selection of tools and packages. It includes modern frontend technologies, comprehensive testing setup, code quality tools, and development utilities to help you build maintainable and scalable applications.

## Screenshots
Explore the screenshots of the starter kit by navigating to the [Screenshots](https://github.com/marjose123/starter-kit/tree/main/.art/screenshots) folder.


## 🛠️ Tech Stack

### Backend
- 🐘 **PHP 8.4** - Latest PHP version with modern features
- 🎨 **Laravel 12** - The latest Laravel framework
- ⚡ **Inertia.js** - Modern monolith approach for SPAs

### Frontend
- 🖼️ **Vue 3** - Progressive JavaScript framework
- 🎨 **Nuxt UI** - Beautiful and accessible UI components
- 🎯 **TailwindCSS 4** - Utility-first CSS framework
- 📦 **TypeScript** - Type-safe JavaScript development
- ⚡ **Vite** - Fast build tool and dev server

### Development Tools
- 🧪 **Pest PHP** - Elegant PHP testing framework
- 🎭 **Playwright** - End-to-end testing for web apps
- 🔍 **PHPStan** - Static analysis for PHP
- 🎨 **Laravel Pint** - Code style fixer for Laravel
- ♻️ **Rector** - Automated refactoring tool
- 📋 **ESLint** - JavaScript/TypeScript linting
- 💅 **Prettier** - Code formatting
- 🐳 **Laravel Sail** - Docker development environment
- 📧 **Mailpit** - Email testing tool

## ✨ Features / Functionality

### Core Features
- 🔐 **User Authentication** - Complete authentication system with Laravel Fortify
- 🛡️ **Two-Factor Authentication (2FA)** - Integration with recovery codes
- 🔒 **Password Confirmation** - Secure page protection with password confirmation middleware
- 🌐 **RESTful API** - Full API authentication with token refresh and management
- 📱 **Session Management** - Device tracking, location data, and session revocation
- 🎨 **Appearance Management** - Custom middleware for theme/appearance handling  
- 📱 **Responsive Design** - Mobile-first approach with TailwindCSS
- ⚡ **SPA Experience** - Seamless navigation with Inertia.js
- 🔔 **Notifications** - Integrated notification system
- 📚 **API Documentation** - Auto-generated API docs with Scramble
- 🔍 **Device Detection** - Comprehensive user agent and location tracking

## 🚀 Installation

### Prerequisites
- PHP 8.4 or higher
- Composer
- Node.js & npm
- Git

### Quick Start with Laravel Installer

If you have the Laravel installer globally installed, you can create a new project using this starter kit:

```bash
composer global require laravel/installer

laravel new example-app --using=marjose123/laravel-nuxtui-starter-kit
```

### Manual Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/marjose123/laravel-nuxtui-starter-kit.git
   cd starter-kit
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

6. **Start development servers**
   ```bash
   composer dev
   # or separately:
   # php artisan serve
   # npm run dev
   ```

### Available Scripts

- `composer dev` - Start all development servers concurrently
- `composer dev:ssr` - Start with server-side rendering
- `composer test` - Run PHP tests
- `composer format` - Format both frontend and backend code
- `composer analyse` - Run static analysis
- `npm run dev` - Start Vite dev server
- `npm run build` - Build for production
- `npm run lint` - Lint frontend code

## 📄 License

The Starter-kit for Laravel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
