<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure;


use BestRouteGenerator\Domain\Graph;
use BestRouteGenerator\Domain\OptimalPathService;
use BestRouteGenerator\Domain\Route;
use Common\Type\Id;

class BruteForceOptimalPathService implements OptimalPathService
{

    public function findOptimalPathInMeters(Graph $graph, Id $source): Route
    {
        $excludeId = null;
        $route[] = $source;
        $totalNodes = count($graph->getPaths());
        for ($i=0;$i<$totalNodes-1;$i++) {
            $previousSource = $source;
            $graph->removePath($excludeId);
            $source = $this->getNextCityWithMinimumDistance($graph, $source);
            $route[] = $source;
            $excludeId = $previousSource;
        }

        print_r($route);
        die();
    }

    /**
     * @param Graph $graph
     * @param Id $source
     * @return Id
     */
    private function getNextCityWithMinimumDistance(Graph $graph, Id $source): Id
    {
        return $graph->getPathByPathId($source)->getNodeIdWithMinimumDistance();
    }
}
