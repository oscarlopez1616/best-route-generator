<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure\Graph;

use BestRouteGenerator\Domain\Graph\Graph;
use BestRouteGenerator\Domain\Graph\OptimalPathService;
use BestRouteGenerator\Domain\Route;
use Common\Type\Id;

class NearestNeighbourOptimalPathService implements OptimalPathService
{

    public function findOptimalPath(Graph $graph, Id $source): Route
    {
        $excludeId = null;
        $route[] = $source;
        $totalNodes = count($graph->getPaths());
        for ($i = 0; $i < $totalNodes - 1; $i++) {
            $previousSource = $source;
            $graph = $graph->removePath($excludeId);
            $source = $graph->getPathByPathId($source)->getNodeIdWithMinDistance( $source);
            $route[] = $source;
            $excludeId = $previousSource;
        }

        return new Route($route);
    }

}
