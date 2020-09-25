<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\ValueObject;

class Vertex extends ValueObject
{
    private string $cityFrom;
    private string $cityTo;
    private float $distance;

    /**
     * Vertex constructor.
     * @param string $cityTo
     * @param float $distance
     */
    public function __construct(string $cityTo, float $distance)
    {
        $this->cityTo = $cityTo;
        $this->distance = $distance;
    }

    /**
     * @return string
     */
    public function getCityTo(): string
    {
        return $this->cityTo;
    }

    /**
     * @return float
     */
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
//        return $this->getCityFrom() === $o->getCityFrom() && $this->getDistance() === $o->getDistance();
    }


}
