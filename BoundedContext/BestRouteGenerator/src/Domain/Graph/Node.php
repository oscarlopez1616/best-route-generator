<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain\Graph;


interface Node
{
    public function getValue(): float;

    public function getUnit(): string;

    public function add(Node $distance): self;

    public function subtract(Node $distance): self;

    public function isLessThan(Node $distance): bool;

    public function isLessOrEqualThan(Node $distance): bool;
}
