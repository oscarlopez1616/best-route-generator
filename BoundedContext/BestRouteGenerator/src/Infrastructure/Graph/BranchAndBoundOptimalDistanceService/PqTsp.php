<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure\Graph\BranchAndBoundOptimalDistanceService;


use SplPriorityQueue;

class PqTsp extends SplPriorityQueue
{
    public function compare($lhs, $rhs) {
        if ($lhs === $rhs) return 0;
        return ($lhs < $rhs) ? 1 : -1;
    }
}
