install:
	composer install
	php artisan key:generate
	php artisan migrate
	php artisan db:seed --class=UserSeeder

serve-setup:
	php artisan serve