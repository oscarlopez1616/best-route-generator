<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;


use BestRouteGenerator\Domain\CityName;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\DistanceService;
use BestRouteGenerator\Domain\Graph;
use BestRouteGenerator\Domain\OptimalPathService;
use BestRouteGenerator\Domain\Path;
use Common\Type\QueryHandler;


class FindTheShortestPathHandler implements QueryHandler
{
    private DistanceService $distanceService;
    private OptimalPathService $optimalService;
    private CityRepository $cityRepository;


    public function __construct(
        DistanceService $distanceService,
        OptimalPathService $optimalService,
        CityRepository $cityRepository
    ) {
        $this->distanceService = $distanceService;
        $this->optimalService = $optimalService;
        $this->cityRepository = $cityRepository;
    }


    public function __invoke(FindTheShortestPathQuery $query): array
    {
        $cities = $this->cityRepository->findAllCities();

        $paths = [];

        foreach ($cities as $city) {
            $distances = [];
            $i = 0;
            foreach ($cities as $city1) {
                if ($city->getId()->getValue() !== $city1->getId()->getValue()) {
                    $distances[$i] = $this->distanceService->findDistanceInMetersBetween2GpsPointsService(
                        $city,
                        $city1
                    );
                }
                $i++;
            }
            $paths[] = new Path($city->getId(), $distances);
        }

        return $this->optimalService->findOptimalPathInMeters(new Graph($paths), new CityName('Beijing'));
    }
}
