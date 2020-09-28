# Info for The BestRouteGenerator BoundedContext:

the root directory for the BestRouteGenerator application is in BoundedContext/BestRouteGenerator/ 

to execute this code it's needed to have docker and docker-compose in your machine 
(this code has been tested with docker 19.03.8 and docker-compose 1.27.4)

## To execute the application follow the next steps
`cd etc/devTools/docker`

`docker-compose up -d`

`docker-compose exec php-fpm composer install`

`docker-compose exec php-fpm php solve.php`

## To Run The Application with Branch&Bound Algorithm:
###create .env if you didn't create
`cp .env.dist .env`
### and set APP_ENV
`APP_ENV=alpha`

## To Run The Application with NearestNeighbour Algorithm &BoundAlgorithm
###create .env if you didn't create
`cp .env.dist .env`
### and set APP_ENV
`APP_ENV=dev`

## To Run The Unit Tests And Acceptance Tests
`./bin/phpunit`



