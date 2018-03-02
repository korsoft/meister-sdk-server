rm -rf database/database.sqlite
touch database/database.sqlite
php artisan migrate
php artisan passport:install
php artisan db:seed

