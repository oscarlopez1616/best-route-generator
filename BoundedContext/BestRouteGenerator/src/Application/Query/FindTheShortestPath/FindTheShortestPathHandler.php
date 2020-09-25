<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;


use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\Coordinate;
use BestRouteGenerator\Domain\DistanceService;
use BestRouteGenerator\Domain\OptimalPathService;
use BestRouteGenerator\Domain\Path;
use Common\Type\QueryHandler;
use http\Exception\RuntimeException;


class FindTheShortestPathHandler implements QueryHandler
{
    private DistanceService $distanceService;
    private OptimalPathService $optimalService;


    public function __construct(DistanceService $distanceService, OptimalPathService $optimalService)
    {
        $this->distanceService = $distanceService;
        $this->optimalService = $optimalService;
    }


    public function __invoke(FindTheShortestPathQuery $query): array
    {

        $cities = $this->read();


        $graph = [];

        foreach ($cities as $cityName => $location) {
            $distances = [];
            $i = 0;
            foreach ($cities as $cityName1 => $location1) {
                if ($cityName !== $cityName1) {
                    $distances[$i] = $this->distanceService->findDistanceInMetersBetween2GpsPointsService(
                        new City(
                            $cityName,
                            new Coordinate(
                                $location['latitude'],
                                $location['longitude']
                            ),
                        ),
                        new City(
                            $cityName1,
                            new Coordinate(
                                $location1['latitude'],
                                $location1['longitude']
                            ),
                        )
                    );
                }
                $i++;
            }
            $graph[] = new Path($cityName, $distances);
        }

        return $this->optimalService->findOptimalPath($graph, 0, 32);
    }

    private function read(): array
    {
        $cities = [];

        $file = fopen("/var/www/data/BoundedContext/BestRouteGenerator/etc/data/"."cities.txt", 'rb');
        if ($file) {
            while (($line = fgets($file)) !== false) {
                $parts = explode(" ", $line);
                $length = count($parts);
                $latitude = $parts[$length - 2];
                $longitude = $parts[$length - 1];
                unset($parts[$length - 1], $parts[$length - 2]);
                $cityName = implode(" ", $parts);
                $cities[$cityName] = ['latitude' => (float)$latitude, 'longitude' => (float)$longitude];
            }
            fclose($file);
        } else {
            throw new RuntimeException('Error opening File');
        }

        return $cities;
    }

}
