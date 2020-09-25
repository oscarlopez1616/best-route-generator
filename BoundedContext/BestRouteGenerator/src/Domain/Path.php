<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\Id;
use Common\Type\ValueObject;

class Path extends ValueObject
{
    private Id $id;

    /**
     * @var Distance[]
     */
    private array $vertices;

    /**
     * Path constructor.
     * @param Id $id
     * @param Distance[] $vertices
     */
    public function __construct(Id $id, array $vertices)
    {
        $this->id = $id;
        $this->vertices = $vertices;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Distance[]
     */
    public function getVertices(): array
    {
        return $this->vertices;
    }


    /**
     * @param self|ValueObject $o
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        //TODO finish
        return $this->id->equals($o->getId());
    }


}
