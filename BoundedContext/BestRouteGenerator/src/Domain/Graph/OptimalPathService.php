<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain\Graph;


use BestRouteGenerator\Domain\Route;
use Common\Type\Id;

interface OptimalPathService
{
    public function findOptimalPath(Graph $graph, Id $source): Route;
}
