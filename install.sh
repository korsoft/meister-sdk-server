rm -rf storage/databases/meister.sqlite
touch storage/databases/meister.sqlite
php artisan migrate
php artisan passport:install
php artisan db:seed

