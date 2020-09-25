<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


interface OptimalPathService
{
    public function findOptimalPath(array $graph, int $cityFrom, int $verticesCount): array;
}
