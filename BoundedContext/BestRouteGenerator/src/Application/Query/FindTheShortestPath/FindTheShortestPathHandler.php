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
use Common\Type\Id;
use Common\Type\QueryHandler;
use Symfony\Component\Config\Definition\Exception\Exception;
use Throwable;


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

        $combinationsWithStartingFromAllCities = null;
        $graph = new Graph($paths);
        $bestRoute = [];
        foreach ($paths as $pathId => $path) {
            $combinationsWithStartingFromAllCities = $this->optimalService->findOptimalPathInMeters(
                $graph,
                new Id($pathId)
            );
            $graph->removePath(new Id($pathId));
            try {
                $bestRoute[] = $combinationsWithStartingFromAllCities->getCityNames()['1'];
            }catch (Throwable $e){}
        }

        print_r($bestRoute);
        die();

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
