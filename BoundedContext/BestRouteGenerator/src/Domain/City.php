<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\AggregateRoot;

class City extends AggregateRoot
{
    private CityName $cityName;
    private Coordinate $coordinate;

    public function __construct(CityName $cityName, Coordinate $coordinate)
    {
        $this->cityName = $cityName;
        $this->coordinate = $coordinate;
    }

    public function getId(): CityName
    {
        return $this->cityName;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }
}
