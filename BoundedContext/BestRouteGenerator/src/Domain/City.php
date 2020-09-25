<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\ValueObject;

class City extends ValueObject
{
    private string $cityName;
    private Coordinate $coordinate;

    public function __construct(string $cityName, Coordinate $coordinate)
    {
        $this->cityName = $cityName;
        $this->coordinate = $coordinate;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    /**
     * @param self|ValueObject $o
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        return $this->cityName === $o->getCityName() && $this->coordinate->equals($o->getCoordinate());
    }

}
