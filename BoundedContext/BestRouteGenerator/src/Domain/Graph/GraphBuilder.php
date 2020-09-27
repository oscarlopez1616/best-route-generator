<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain\Graph;


use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\DistanceService;

class GraphBuilder
{
    private DistanceService $distanceService;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }


    /**
     * @param City[] $cities
     * @return Graph
     */
    public function buildGraphFromCitiesWithAllNodesConnectedBetweenThem(array $cities): Graph
    {
        $paths = [];

        foreach ($cities as $city) {
            $distances = [];

            foreach ($cities as $city1) {
                if ($city->getId()->getValue() !== $city1->getId()->getValue()) {
                    $distances[$city1->getId()
                        ->getValue()] = $this->distanceService->findDistanceInMetersBetween2GpsPointsService(
                        $city,
                        $city1
                    );
                }
            }
            $paths[$city->getId()->getValue()] = new Path($distances);
        }

        return new Graph($paths);

    }

}
