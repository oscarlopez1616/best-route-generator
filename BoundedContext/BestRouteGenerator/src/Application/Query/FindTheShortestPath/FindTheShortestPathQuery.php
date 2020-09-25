<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Query\FindTheShortestPath;

use Common\Type\Query;

class FindTheShortestPathQuery implements Query
{
    private string $filename;


    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }


    public function getFilename(): string
    {
        return $this->filename;
    }

}
