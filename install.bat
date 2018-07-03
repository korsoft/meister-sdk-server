@ECHO OFF 
DEL /Q /S /F storage\databases\meister.sqlite
type NUL > storage\databases\meister.sqlite
php artisan migrate
php artisan passport:install
php artisan db:seed