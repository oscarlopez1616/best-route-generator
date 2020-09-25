<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\Id;

interface OptimalPathService
{

    public function findOptimalPathInMeters(Graph $graph, Id $source): array;
}
