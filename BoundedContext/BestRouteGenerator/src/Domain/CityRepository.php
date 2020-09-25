<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


interface CityRepository
{
    /**
     * @return City[]
     */
    public function findAllCities(): array;
}
