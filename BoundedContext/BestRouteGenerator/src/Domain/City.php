<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\AggregateRoot;
use Common\Type\Id;

class City extends AggregateRoot
{
    private Id $cityName;
    private Coordinate $coordinate;

    public function __construct(Id $cityName, Coordinate $coordinate)
    {
        $this->cityName = $cityName;
        $this->coordinate = $coordinate;
    }

    public function getId(): Id
    {
        return $this->cityName;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }
}
