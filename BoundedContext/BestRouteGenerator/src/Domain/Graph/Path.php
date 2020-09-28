<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain\Graph;


use BestRouteGenerator\Domain\Coordinate;
use BestRouteGenerator\Domain\Distance;
use Common\Type\Exception\DomainException;
use Common\Type\Id;
use Common\Type\ValueObject;

class Path extends ValueObject
{
    /**
     * @var Distance[]
     */
    private array $nodes;


    private Coordinate $coordinate;

    /**
     * Path constructor.
     * @param Distance[] $nodes
     * @param Coordinate $coordinate
     */
    public function __construct(array $nodes, Coordinate $coordinate)
    {
        $this->nodes = $nodes;
        $this->coordinate = $coordinate;
    }

    /**
     * @return Distance[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }


    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }


    public function removeNode(Id $id): self
    {
        $this->getIndexByNodeId($id);
        $nodes = $this->nodes;
        unset($nodes[$id->getValue()]);
        return new self($nodes, $this->coordinate);
    }

    public function getNodeIdWithMinDistance(): Id
    {
        $copyNodes = $this->nodes;
        uasort(
            $copyNodes,
            static function (Distance $a, Distance $b) {
                return $a->subtract($b)->getValue();
            }

        );

        return new Id(array_keys($copyNodes)[0]);
    }

    /**
     * @param Id $id
     * @return string
     */
    private function getIndexByNodeId(Id $id): string
    {
        foreach ($this->nodes as $nodeId => $path) {
            if ($nodeId === $id->getValue()) {
                return $nodeId;
            }
        }

        throw new DomainException(sprintf('this %s does not exist in this path', $id));
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
