<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;


use BestRouteGenerator\Application\Dto\RouteDto;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\DistanceService;
use BestRouteGenerator\Domain\Graph\Graph;
use BestRouteGenerator\Domain\Graph\OptimalPathService;
use BestRouteGenerator\Domain\Graph\Path;
use Common\Type\Id;
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

        $graph = new Graph($paths);

        $route = $this->optimalService->findOptimalPath(
            $graph,
            new Id('Beijing')
        );

        return RouteDto::assemble($route);
    }
}
