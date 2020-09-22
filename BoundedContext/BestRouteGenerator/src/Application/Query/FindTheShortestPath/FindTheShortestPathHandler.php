<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;


use Common\Type\QueryHandler;


class FindTheShortestPathHandler implements QueryHandler
{

    public function __invoke(FindTheShortestPathQuery $query): string
    {
        return 'hola';
    }

}
