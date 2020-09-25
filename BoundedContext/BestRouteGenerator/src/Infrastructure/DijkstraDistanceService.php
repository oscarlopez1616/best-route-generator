<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure;


use BestRouteGenerator\Domain\Distance;
use BestRouteGenerator\Domain\Graph;
use BestRouteGenerator\Domain\OptimalPathService;
use BestRouteGenerator\Domain\Path;
use Common\Type\Id;

class DijkstraDistanceService implements OptimalPathService
{

    private const INT_MAX = 0x7FFFFFFF;


    public function findOptimalPathInMeters(Graph $graph, Id $source): array
    {

        $sourceAsIndex = $graph->getIndexByPathId($source);


        /**
         * @var Distance
         */
        $distance = [];
        $shortestPathTreeSet = [];

        $verticesCount = count($graph->getPaths());

        for ($i = 0; $i < $verticesCount; ++$i) {
            $distance[$i] = Distance::createInMeters(self::INT_MAX);
            $shortestPathTreeSet[$i] = false;
        }

        $distance[$sourceAsIndex] = Distance::createInMeters(0);

        for ($count = 0; $count < $verticesCount - 1; ++$count) {
            $u = $this->minimumDistanceInMeters($distance, $shortestPathTreeSet, $verticesCount);
            $shortestPathTreeSet[$u] = true;

            for ($v = 0; $v < $verticesCount; ++$v) {
                if (
                    !$shortestPathTreeSet[$v]
                    && !$distance[$u]->equals(Distance::createInMeters(self::INT_MAX))
                    && $graph->getPaths()[$u]->getVertices()[$v]->getDistance()
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
    private function minimumDistanceInMeters(array $distance, array $shortestPathTreeSet, int $verticesCount): int
    {
        $min = Distance::createInMeters(self::INT_MAX);
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
     * @return array
     */
    private function formatResult(array $graph, array $distance, int $verticesCount): array
    {
        $result = [];
        $totalDistance = 0;
        for ($i = 0; $i < $verticesCount; ++$i) {
            $result[$graph[$i]->getId()->getValue()] = $distance[$i];
            $totalDistance += $distance[$i]->getDistance();
        }
        $result['totalDistance'] = $totalDistance;
        return $result;
    }
}
