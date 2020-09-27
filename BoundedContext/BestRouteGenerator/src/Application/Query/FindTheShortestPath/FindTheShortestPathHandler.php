<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;


use BestRouteGenerator\Application\Dto\RouteDto;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\Graph\GraphBuilder;
use BestRouteGenerator\Domain\Graph\OptimalPathService;
use Common\Type\Id;
use Common\Type\QueryHandler;


class FindTheShortestPathHandler implements QueryHandler
{
    private CityRepository $cityRepository;
    private GraphBuilder $graphBuilder;
    private OptimalPathService $optimalService;

    public function __construct(
        CityRepository $cityRepository,
        GraphBuilder $graphBuilder,
        OptimalPathService $optimalService

    ) {
        $this->cityRepository = $cityRepository;
        $this->graphBuilder = $graphBuilder;
        $this->optimalService = $optimalService;
    }


    public function __invoke(FindTheShortestPathQuery $query): RouteDto
    {
        $graph = $this->graphBuilder->buildGraphFromCities($this->cityRepository->findAllCities());
        $route = $this->optimalService->findOptimalPath(
            $graph,
            new Id($query->getCityFrom())
        );
        return RouteDto::assemble($route);
    }
}
