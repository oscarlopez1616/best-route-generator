<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\Id;
use Common\Type\ValueObject;

class Route extends ValueObject
{
    /**
     * @var Id[]
     */
    private array $cityNames;

    /**
     * Route constructor.
     * @param Id[] $cityNames
     */
    public function __construct(array $cityNames)
    {
        $this->cityNames = $cityNames;
    }

    /**
     * @return Id[]
     */
    public function getCityNames(): array
    {
        return $this->cityNames;
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
