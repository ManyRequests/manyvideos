# ManyVideos

## Installation

Install dependencies:

```bash
php composer install
npm run install
```

Generate the app key

```bash
php artisan key:generate
```

Copy the `.env.example` file to `.env` and fill in the database information

```bash
cp .env.example .env

# REMEMBER SET THE DATABASE INFORMATION AND PUSHER KEYS

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Run the migrations

```bash
php artisan migrate
```

Run the server

```bash
php artisan serve
```

Run the worker

```bash
php artisan queue:work
```

