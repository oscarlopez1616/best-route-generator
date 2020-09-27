<?php
declare(strict_types=1);


namespace BestRouteGenerator\Tests\ObjectMother\BoundedContext\BestRouteGenerator\Domain;


use BestRouteGenerator\Domain\Distance;
use BestRouteGenerator\Domain\Graph\Graph;
use BestRouteGenerator\Domain\Graph\Path;

class GraphObjectMother
{

    public static function adjacencyGraphWithExampleDataAllNodesConnected(): Graph
    {
        return new Graph(
            [
                'Barcelona' =>
                    new Path(
                        [
                            'Madrid' => Distance::createInMeters(504000),
                            'Ourense' => Distance::createInMeters(839000)
                        ]
                    ),
                'Madrid' =>
                    new Path(
                        [
                            'Barcelona' => Distance::createInMeters(504000),
                            'Ourense' => Distance::createInMeters(408000)
                        ]
                    ),
                'Ourense' =>
                    new Path(
                        [
                            'Barcelona' => Distance::createInMeters(839000),
                            'Madrid' => Distance::createInMeters(408000)
                        ]
                    ),
            ]
        );

    }

}
