<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;


use BestRouteGenerator\Application\Dto\RouteDto;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\Distance;
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


    public function __invoke(FindTheShortestPathQuery $query): ?RouteDto
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

        $combinationsWithStartingFromAllCities = [];
        foreach ($paths as $path) {
            $combinationsWithStartingFromAllCities[] = $this->optimalService->findOptimalPathInMeters(new Graph($paths), $path->getId());
        }

        $bestRoute = null;
        $initialState = Distance::createInMeters(INF);

        foreach ($combinationsWithStartingFromAllCities as $route) {
            if ($route->getTotalDistance()->isLessThan($initialState)) {
                $bestRoute = $route;
                $initialState = $route->getTotalDistance();
            }
        }

        return RouteDto::assemble($bestRoute);
    }
}
