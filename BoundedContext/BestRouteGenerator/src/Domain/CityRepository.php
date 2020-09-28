<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use BestRouteGenerator\Infrastructure\Graph\BranchAndBoundOptimalDistanceService\BranchAndBoundDistanceOptimalDistance;

interface CityRepository
{
    /**
     * @return City[]
     */
    public function findAllCities(): array;
}
