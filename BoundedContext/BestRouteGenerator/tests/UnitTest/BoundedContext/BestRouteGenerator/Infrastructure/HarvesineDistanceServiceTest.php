<?php
declare(strict_types=1);


namespace BestRouteGenerator\Tests\UnitTest\BoundedContext\BestRouteGenerator\Infrastructure;


use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\Coordinate;
use BestRouteGenerator\Domain\Distance;
use BestRouteGenerator\Infrastructure\HarvesineDistanceService;
use Common\Type\Id;
use PHPUnit\Framework\TestCase;

class HarvesineDistanceServiceTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnDistance(): void
    {
        $distanceService = new HarvesineDistanceService();
        $distance = $distanceService->findDistanceInMetersBetween2GpsPointsService(
            new City(
                new Id('Beijing'),
                new Coordinate(39.93, 116.40)
            ),
            new City(
                new Id('Tokyo'),
                new Coordinate(35.40, 139.45)
            )
        );

        $this->assertEquals(Distance::createInMeters(2084050.496008833), $distance);
    }

    /**
     * @test
     */
    public function itShouldReturnDistanceWithZero(): void
    {
        $distanceService = new HarvesineDistanceService();
        $distance = $distanceService->findDistanceInMetersBetween2GpsPointsService(
            new City(
                new Id('Beijing'),
                new Coordinate(39.93, 116.40)
            ),
            new City(
                new Id('Beijing'),
                new Coordinate(39.93, 116.40)
            ),
        );

        $this->assertEquals(Distance::createInMeters(0), $distance);
    }

}
