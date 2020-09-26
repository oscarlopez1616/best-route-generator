<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\ValueObject;

class Path extends ValueObject
{
    /**
     * @var Distance[]
     */
    private array $nodes;

    /**
     * Path constructor.
     * @param Distance[] $vertices
     */
    public function __construct(array $vertices)
    {
        $this->nodes = $vertices;
    }

    /**
     * @return Distance[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }


    /**
     * @param self|ValueObject $o
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        //TODO finish
    }


}
