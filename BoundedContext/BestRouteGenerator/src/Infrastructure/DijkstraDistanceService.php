<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure;


use BestRouteGenerator\Domain\CityName;
use BestRouteGenerator\Domain\Distance;
use BestRouteGenerator\Domain\Graph;
use BestRouteGenerator\Domain\OptimalPathService;
use BestRouteGenerator\Domain\Path;
use BestRouteGenerator\Domain\Route;
use Common\Type\Id;

class DijkstraDistanceService implements OptimalPathService
{
    public function findOptimalPathInMeters(Graph $graph, Id $source): Route
    {

        $sourceAsIndex = $graph->getIndexByPathId($source);

        /**
         * @var Distance
         */
        $distance = [];
        $shortestPathTreeSet = [];

        $verticesCount = count($graph->getPaths());

        for ($i = 0; $i < $verticesCount; ++$i) {
            $distance[$i] = Distance::createInMeters(INF);
            $shortestPathTreeSet[$i] = false;
        }

        $distance[$sourceAsIndex] = Distance::createInMeters(0);

        for ($count = 0; $count < $verticesCount - 1; ++$count) {
            $u = $this->minimumDistanceIndex($distance, $shortestPathTreeSet, $verticesCount);
            $shortestPathTreeSet[$u] = true;

            for ($v = 0; $v < $verticesCount; ++$v) {
                if (
                    !$shortestPathTreeSet[$v]
                    && $distance[$u]->getDistance() !== Distance::createInMeters(INF)->getDistance()
                    && (bool)$graph->getPaths()[$u]->getVertices()[$v]->getDistance()
                    && $distance[$u]->addDistance($graph->getPaths()[$u]->getVertices()[$v])->isLessThan($distance[$v])
                ) {
                    $distance[$v] = $distance[$u]->addDistance($graph->getPaths()[$u]->getVertices()[$v]);
                }
            }
        }

        return $this->formatResult($graph->getPaths(), $distance, $verticesCount);
    }

    /**
     * @param Distance[] $distance
     * @param bool[] $shortestPathTreeSet
     * @param int $verticesCount
     * @return int
     */
    private function minimumDistanceIndex(array $distance, array $shortestPathTreeSet, int $verticesCount): int
    {
        $min = Distance::createInMeters(INF);
        $minIndex = 0;

        for ($v = 0; $v < $verticesCount; ++$v) {
            if ($shortestPathTreeSet[$v] === false && $distance[$v]->isLessOrEqualThan($min)) {
                $min = $distance[$v];
                $minIndex = $v;
            }
        }

        return $minIndex;
    }

    /**
     * @param Path[] $graph
     * @param Distance[] $distance
     * @param int $verticesCount
     * @return Route
     */
    private function formatResult(array $graph, array $distance, int $verticesCount): Route
    {
        $result = [];
        $totalDistance = 0;

        for ($i = 0; $i < $verticesCount; ++$i) {
            $result[$graph[$i]->getId()->getValue()] = $distance[$i];
            $totalDistance += $distance[$i]->getDistance();
        }
        asort($result);

        $cities = [];

        foreach ($result as $cityName => $distanceBetweenCities) {
            $cities[] = new CityName($cityName);
        }

        return new Route($cities, Distance::createInMeters($totalDistance));

    }
}
