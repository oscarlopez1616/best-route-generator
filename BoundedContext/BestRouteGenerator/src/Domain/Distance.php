<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\ValueObject;
use RuntimeException;

class Distance extends ValueObject
{
    public const METERS = 'meters';

    private float $distance;
    private string $unit;

    private function __construct(float $distance, string $unit)
    {
        $this->distance = $distance;
        $this->unit = $unit;
    }

    public static function createInMeters(float $distance): self
    {
        return new self($distance, self::METERS);
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function addDistance(Distance $distance): self
    {
        if ($this->getUnit() !== $distance->getUnit()) {
            throw new RuntimeException(
                sprintf('the units to add Distances needs to be the same %s instead', $distance->getUnit())
            );
        }
        return new self($this->distance+$distance->getDistance(), $this->getUnit());
    }

    public function isLessThan(Distance $distance): bool
    {
        if ($this->getUnit() !== $distance->getUnit()) {
            throw new RuntimeException(
                sprintf('the units to compare Distances needs to be the same %s instead', $distance->getUnit())
            );
        }
        return $this->getDistance() < $distance->getDistance();
    }

    public function isLessOrEqualThan(Distance $distance): bool
    {
        if ($this->getUnit() !== $distance->getUnit()) {
            throw new RuntimeException(
                sprintf('the units to compare Distances needs to be the same %s instead', $distance->getUnit())
            );
        }
        return $this->getDistance() <= $distance->getDistance();
    }

    /**
     * @param self|ValueObject $o
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        return $this->distance === $o->getDistance() && $this->getUnit() === $o->getUnit();
    }


}
