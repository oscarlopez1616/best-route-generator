<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;


use BestRouteGenerator\Application\Dto\RouteDto;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\Graph\AdjacencyGraphBuilder;
use BestRouteGenerator\Domain\Graph\OptimalPathService;
use BestRouteGenerator\Infrastructure\Graph\TspBranchBound;
use Common\Type\Id;
use Common\Type\QueryHandler;


class FindTheShortestPathQueryHandler implements QueryHandler
{
    private CityRepository $cityRepository;
    private AdjacencyGraphBuilder $adjacencyGraphBuilder;
    private OptimalPathService $optimalService;

    public function __construct(
        CityRepository $cityRepository,
        AdjacencyGraphBuilder $adjacencyGraphBuilder,
        OptimalPathService $optimalService

    ) {
        $this->cityRepository = $cityRepository;
        $this->adjacencyGraphBuilder = $adjacencyGraphBuilder;
        $this->optimalService = $optimalService;
    }


    public function __invoke(FindTheShortestPathQuery $query): RouteDto
    {
        $graph = $this->adjacencyGraphBuilder->buildGraphFromCitiesWithAllNodesConnectedBetweenThem(
            $this->cityRepository->findAllCities()
        );

        $route = $this->optimalService->findOptimalPath(
            $graph,
            new Id($query->getCityFrom())
        );

        return RouteDto::assemble($route);
    }
}
