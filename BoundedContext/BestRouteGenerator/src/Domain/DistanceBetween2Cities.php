<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\ValueObject;

class DistanceBetween2Cities extends ValueObject
{
    private City $cityFrom;
    private City $cityTo;
    private float $distance;

    public function __construct(City $cityFrom, City $cityTo, float $distance)
    {
        $this->cityFrom = $cityFrom;
        $this->cityTo = $cityTo;
        $this->distance = $distance;
    }

    public function getCityFrom(): City
    {
        return $this->cityFrom;
    }

    public function getCityTo(): City
    {
        return $this->cityTo;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    /**
     * @param self|ValueObject $o
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        // TODO: Implement equalValues() method.
    }


}
