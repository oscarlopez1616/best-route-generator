<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;

use Common\Type\Query;

final class FindTheShortestPathQuery implements Query
{
    private string $cityFrom;

    public function __construct(string $cityFrom)
    {
        $this->cityFrom = $cityFrom;
    }

    public function getCityFrom(): string
    {
        return $this->cityFrom;
    }

}
