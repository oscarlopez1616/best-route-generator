<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\ValueObject;

class Route extends ValueObject
{
    /**
     * @var CityName[]
     */
    private array $cityNames;
    private Distance $totalDistance;

    /**
     * Route constructor.
     * @param City[] $cityNames
     * @param Distance $totalDistance
     */
    public function __construct(array $cityNames, Distance $totalDistance)
    {
        $this->cityNames = $cityNames;
        $this->totalDistance = $totalDistance;
    }

    /**
     * @return CityName[]
     */
    public function getCityNames(): array
    {
        return $this->cityNames;
    }

    
    public function getTotalDistance(): Distance
    {
        return $this->totalDistance;
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
