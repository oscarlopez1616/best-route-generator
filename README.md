# To execute the application follow the next steps
`cd etc/devTools/docker`

`docker-compose up -d`

`docker-compose exec php-fpm composer install`

`docker-compose exec php-fpm php solve.php`

# To Run The Unit Tests And Acceptance Tests
`./bin/phpunit`
