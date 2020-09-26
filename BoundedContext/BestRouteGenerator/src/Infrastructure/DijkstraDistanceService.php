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

        $graphIds = array_keys($graph->getPaths());

        foreach ($graphIds as $graphId) {
            $distance[$graphId] = Distance::createInMeters(INF);
            $shortestPathTreeSet[$graphId] = false;
        }

        $distance[$sourceAsIndex] = Distance::createInMeters(0);

        foreach ($graphIds as $pathsGraphId) {
            $minimumDistanceIndex = $this->minimumDistanceIndex($distance, $shortestPathTreeSet, $graphIds);
            $shortestPathTreeSet[$minimumDistanceIndex] = true;

            foreach ($graphIds as $graphId) {
                if (
                    !$shortestPathTreeSet[$graphId]
                    && $distance[$minimumDistanceIndex]->getDistance() !== Distance::createInMeters(INF)->getDistance()
                    && (bool)$graph->getPaths()[$minimumDistanceIndex]->getNodes()[$graphId]->getDistance()
                    && $distance[$minimumDistanceIndex]->addDistance($graph->getPaths()[$minimumDistanceIndex]->getNodes()[$graphId])
                        ->isLessThan($distance[$graphId])
                ) {
                    $distance[$graphId] = $distance[$minimumDistanceIndex]->addDistance($graph->getPaths()[$minimumDistanceIndex]->getNodes()[$graphId]);
                }
            }
        }

        return $this->formatResult($graph->getPaths(), $distance, $graphIds);
    }

    /**
     * @param Distance[] $distance
     * @param bool[] $shortestPathTreeSet
     * @param string[] $graphIds
     * @return string
     */
    private function minimumDistanceIndex(array $distance, array $shortestPathTreeSet, array $graphIds): string
    {
        $min = Distance::createInMeters(INF);
        $minIndex = 0;

        foreach ($graphIds as $graphId) {
            if ($shortestPathTreeSet[$graphId] === false && $distance[$graphId]->isLessOrEqualThan($min)) {
                $min = $distance[$graphId];
                $minIndex = $graphId;
            }
        }

        return $minIndex;
    }

    /**
     * @param Path[] $graph
     * @param Distance[] $distance
     * @param string[] $graphIds
     * @return Route
     */
    private function formatResult(array $graph, array $distance, array $graphIds): Route
    {
        $result = [];
        $totalDistance = 0;

        foreach ($graphIds as $graphId) {
            $result[$graphId] = $distance[$graphId];
            $totalDistance += $distance[$graphId]->getDistance();
        }

        $cities = [];

        foreach ($result as $cityName => $distanceBetweenCities) {
            $cities[] = new CityName($cityName);
        }

        return new Route($cities, Distance::createInMeters($totalDistance));

    }
}
