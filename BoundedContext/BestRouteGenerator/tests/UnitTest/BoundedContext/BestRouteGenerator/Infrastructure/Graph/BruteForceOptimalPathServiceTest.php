<?php
declare(strict_types=1);


namespace BestRouteGenerator\Tests\UnitTest\BoundedContext\BestRouteGenerator\Infrastructure\Graph;


use BestRouteGenerator\Domain\Route;
use BestRouteGenerator\Infrastructure\Graph\BruteForceOptimalPathService;
use BestRouteGenerator\Tests\ObjectMother\BoundedContext\BestRouteGenerator\Domain\GraphObjectMother;
use Common\Type\Exception\DomainException;
use Common\Type\Id;
use PHPUnit\Framework\TestCase;

class BruteForceOptimalPathServiceTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldReturnRoute(): void
    {
        $BruteForceOptimalPathService = new BruteForceOptimalPathService();
        $route = $BruteForceOptimalPathService->findOptimalPath(
            GraphObjectMother::adjacencyGraphWithExampleDataAllNodesConnected(),
            new Id('Barcelona')
        );

        $this->assertEquals(
            new Route(
                [
                    new Id('Barcelona'),
                    new Id('Madrid'),
                    new Id('Ourense')
                ]
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
        $BruteForceOptimalPathService = new BruteForceOptimalPathService();
        $BruteForceOptimalPathService->findOptimalPath(
            GraphObjectMother::adjacencyGraphWithExampleDataAllNodesConnected(),
            new Id('')
        );
    }

}
