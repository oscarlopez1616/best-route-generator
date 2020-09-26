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
        $q= $graph;
    }
}
