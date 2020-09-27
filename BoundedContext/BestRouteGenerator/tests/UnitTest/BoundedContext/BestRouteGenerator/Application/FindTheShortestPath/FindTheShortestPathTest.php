<?php
declare(strict_types=1);


namespace BestRouteGenerator\Tests\UnitTest\BoundedContext\BestRouteGenerator\Application\FindTheShortestPath;


use BestRouteGenerator\Application\Dto\RouteDto;
use BestRouteGenerator\Application\Query\FindTheShortestPath\FindTheShortestPathHandler;
use BestRouteGenerator\Application\Query\FindTheShortestPath\FindTheShortestPathQuery;
use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\Coordinate;
use BestRouteGenerator\Domain\Graph\AdjacencyGraphBuilder;
use BestRouteGenerator\Domain\Route;
use BestRouteGenerator\Infrastructure\Graph\BruteForceOptimalPathService;
use BestRouteGenerator\Infrastructure\HarvesineDistanceService;
use BestRouteGenerator\Tests\ObjectMother\BoundedContext\BestRouteGenerator\Domain\GraphObjectMother;
use Common\Domain\Exception\DomainException;
use Common\Type\Id;
use PHPUnit\Framework\TestCase;

class FindTheShortestPathTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnARoute(): void
    {
        $findTheShortestPathQuery = new FindTheShortestPathQuery('Beijing');

        $cityRepository = $this->createMock(CityRepository::class);

        $cityRepository
            ->expects(self::once())
            ->method('findAllCities')
            ->willReturn(
                [
                    new City(
                        new Id('Beijing'),
                        new Coordinate(39.93, 116.4)
                    ),
                    new City(
                        new Id('Tokyo'),
                        new Coordinate(35.4, 139.45)
                    ),
                    new City(
                        new Id('Vladivostok'),
                        new Coordinate(43.8, 131.54)
                    ),
                ]
            );

        $findTheShortestPathHandler = new FindTheShortestPathHandler(
            $cityRepository,
            new AdjacencyGraphBuilder(new HarvesineDistanceService()),
            new BruteForceOptimalPathService()
        );

        $route = $findTheShortestPathHandler->__invoke($findTheShortestPathQuery);

        $this->assertEquals(
            RouteDto::assemble(
                new Route(
                    [
                        new Id('Beijing'),
                        new Id('Vladivostok'),
                        new Id('Tokyo')
                    ]
                )
            ),
            $route
        );

    }


    /**
     * @test
     */
    public function itShouldThrownDomainException(): void
    {
        $this->expectException(DomainException::class);
        $findTheShortestPathQuery = new FindTheShortestPathQuery('');

        $cityRepository = $this->createMock(CityRepository::class);
        $graphBuilder = $this->createMock(AdjacencyGraphBuilder::class);
        $graph = GraphObjectMother::adjacencyGraphWithExampleDataAllNodesConnected();

        $graphBuilder
            ->expects(self::once())
            ->method('buildGraphFromCitiesWithAllNodesConnectedBetweenThem')
            ->with([])
            ->willReturn($graph);

        $findTheShortestPathHandler = new FindTheShortestPathHandler(
            $cityRepository,
            $graphBuilder,
            new BruteForceOptimalPathService()
        );

        $findTheShortestPathHandler->__invoke($findTheShortestPathQuery);

    }

}
