<?php
declare(strict_types=1);


namespace BestRouteGenerator\Tests\UnitTest\BoundedContext\BestRouteGenerator\Application\FindTheShortestPath;


use BestRouteGenerator\Application\Query\FindTheShortestPath\FindTheShortestPathHandler;
use BestRouteGenerator\Application\Query\FindTheShortestPath\FindTheShortestPathQuery;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\DistanceService;
use BestRouteGenerator\Domain\Graph\OptimalPathService;
use Common\Domain\Exception\DomainException;
use PHPUnit\Framework\TestCase;

class FindTheShortestPathTest extends TestCase
{

    /**
     * @test
     */
    public function handleShouldThrownDomainException(): void
    {
        $this->expectException(DomainException::class);
        $findTheShortestPathQuery = new FindTheShortestPathQuery('');

        $cityRepository = $this->createMock(CityRepository::class);
        $distanceService = $this->createMock(DistanceService::class);
        $optimalService = $this->createMock(OptimalPathService::class);
        $optimalService
            ->expects(self::once())
            ->method('findOptimalPath')
            ->with(
                $graph,
                $id
            );

        $findTheShortestPathHandler = new FindTheShortestPathHandler(
            $cityRepository,
            $distanceService,
            $optimalService
        );
        $findTheShortestPathHandler->__invoke($findTheShortestPathQuery);

    }

}
