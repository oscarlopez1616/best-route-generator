<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use BestRouteGenerator\Domain\Graph\Node;
use Common\Type\Exception\DomainException;
use Common\Type\ValueObject;

class Distance extends ValueObject implements Node
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

    public function getValue(): float
    {
        return $this->distance;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function add(Node $distance): self
    {
        if ($this->getUnit() !== $distance->getUnit()) {
            throw new DomainException(
                sprintf('the units to add Distances needs to be the same %s instead', $distance->getUnit())
            );
        }
        return new self($this->distance + $distance->getValue(), $this->getUnit());
    }

    public function subtract(Node $distance): self
    {
        if ($this->getUnit() !== $distance->getUnit()) {
            throw new DomainException(
                sprintf('the units to add Distances needs to be the same %s instead', $distance->getUnit())
            );
        }
        return new self($this->distance - $distance->getValue(), $this->getUnit());
    }

    public function isLessThan(Node $distance): bool
    {
        if ($this->getUnit() !== $distance->getUnit()) {
            throw new DomainException(
                sprintf('the units to compare Distances needs to be the same %s instead', $distance->getUnit())
            );
        }
        return $this->getValue() < $distance->getValue();
    }

    public function isLessOrEqualThan(Node $distance): bool
    {
        if ($this->getUnit() !== $distance->getUnit()) {
            throw new DomainException(
                sprintf('the units to compare Distances needs to be the same %s instead', $distance->getUnit())
            );
        }
        return $this->getValue() <= $distance->getValue();
    }

    /**
     * @param self|ValueObject $o
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        return $this->distance === $o->getValue() && $this->getUnit() === $o->getUnit();
    }


}
