
## Installation

Run these commands in the project folder
```
cp .env.example .env
composer install
php artisan key:generate
```

Provide database credentials in the .env file. After that:
```
php artisan migrate --seed
php artisan serve
```
