# Info for The BestRouteGenerator BoundedContext:

the root directory for the BestRouteGenerator application is in BoundedContext/BestRouteGenerator/ 

## To execute the application follow the next steps
`cd etc/devTools/docker`

`cp .env.dist .env`

`docker-compose up -d`

`docker-compose exec php-fpm composer install`

`docker-compose exec php-fpm php solve.php`

## To Run The Unit Tests And Acceptance Tests
`./bin/phpunit`
