<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure;


use BestRouteGenerator\Domain\OptimalPathService;
use BestRouteGenerator\Domain\Path;

class DijkstraDistanceService implements OptimalPathService
{

    private const INT_MAX = 0x7FFFFFFF;

    /**
     * @param Path[] $graph
     * @param int $source
     * @param int $verticesCount
     * @return array
     */
    public function findOptimalPath(array $graph, int $source, int $verticesCount): array
    {
        $distance = [];
        $shortestPathTreeSet = [];

        for ($i = 0; $i < $verticesCount; ++$i) {
            $distance[$i] = self::INT_MAX;
            $shortestPathTreeSet[$i] = false;
        }

        $distance[$source] = 0;

        for ($count = 0; $count < $verticesCount - 1; ++$count) {
            $u = $this->minimumDistance($distance, $shortestPathTreeSet, $verticesCount);
            $shortestPathTreeSet[$u] = true;

            for ($v = 0; $v < $verticesCount; ++$v) {
                if (
                    !$shortestPathTreeSet[$v]
                    && $distance[$u] !== self::INT_MAX
                    && $graph[$u]->getVertices()[$v]->getDistance()
                    && $distance[$u] + $graph[$u]->getVertices()[$v]->getDistance() < $distance[$v]) {
                    $distance[$v] = $distance[$u] + $graph[$u]->getVertices()[$v]->getDistance();
                }
            }
        }

        return $this->formatResult($graph, $distance, $verticesCount);
    }

    private function minimumDistance(array $distance, array $shortestPathTreeSet, int $verticesCount): int
    {
        $min = self::INT_MAX;
        $minIndex = 0;

        for ($v = 0; $v < $verticesCount; ++$v) {
            if ($shortestPathTreeSet[$v] === false && $distance[$v] <= $min) {
                $min = $distance[$v];
                $minIndex = $v;
            }
        }

        return $minIndex;
    }

    /**
     * @param Path[] $graph
     * @param float[] $distance
     * @param int $verticesCount
     * @return array
     */
    private function formatResult(array $graph, array $distance, int $verticesCount): array
    {
        $result = [];
        $totalDistance = 0;
        for ($i = 0; $i < $verticesCount; ++$i) {
            $result[$graph[$i]->getCityFrom()] = $distance[$i];
            $totalDistance += $distance[$i];
        }
        $result['totalDistance'] = $totalDistance;
        return $result;
    }
}
