<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\ValueObject;

class Path extends ValueObject
{
    private string $cityFrom;

    /**
     * @var vertex[]
     */
    private array $vertices;

    /**
     * Path constructor.
     * @param string $cityFrom
     * @param vertex[] $vertices
     */
    public function __construct(string $cityFrom, array $vertices)
    {
        $this->cityFrom = $cityFrom;
        $this->vertices = $vertices;
    }

    /**
     * @return string
     */
    public function getCityFrom(): string
    {
        return $this->cityFrom;
    }

    /**
     * @return vertex[]
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
        // TODO: Implement equalValues() method.
    }


}
