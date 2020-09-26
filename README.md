# To execute the application follow the next steps
`cd etc/devTools/`

`docker-compose build`

`docker-compose up -d`

`docker-compose exec php-fpm composer install`

`docker-compose exec php-fpm php solve.php`
