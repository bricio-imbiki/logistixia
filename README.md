# Logistixia

Logistixia is a Laravel-based logistics management application. It provides features for managing clients, marchandises, routes, and more.

## Features

- Client and merchandise management
- Route and delivery tracking
- Responsive UI with Tailwind CSS and Blade components
- Asset bundling via Vite
- Code linting with Pint and Prettier
- Automated testing with PHPUnit and Pest

## Requirements

- PHP >= 8.2
- Node.js >= 18
- Composer

## Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/yourusername/logistixia.git
   cd logistixia
   ```

2. **Install PHP dependencies:**
   ```sh
   composer install
   ```

3. **Install Node dependencies:**
   ```sh
   npm install
   ```

4. **Copy environment file and generate app key:**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

5. **Build frontend assets:**
   ```sh
   npm run build
   ```

6. **Run migrations (if needed):**
   ```sh
   php artisan migrate
   ```

## Development

- Start the development server:
  ```sh
  php artisan serve
  npm run dev
  ```

## Testing

- Run tests:
  ```sh
  ./vendor/bin/phpunit
  ```

## Code Quality

- Run Pint (PHP linter):
  ```sh
  vendor/bin/pint
  ```
- Run Prettier (JS/CSS/Blade linter):
  ```sh
  npx prettier --write .
  ```

