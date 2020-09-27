<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain\Graph;


use BestRouteGenerator\Domain\Distance;
use Common\Domain\Exception\DomainException;
use Common\Type\Id;
use Common\Type\ValueObject;

class Path extends ValueObject
{
    /**
     * @var Distance[]
     */
    private array $nodes;

    /**
     * Path constructor.
     * @param Distance[] $nodes
     */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @return Distance[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function removeNode(Id $id): self
    {
        $this->getIndexByNodeId($id);
        $nodes = $this->nodes;
        unset($nodes[$id->getValue()]);
        return new self($nodes);
    }

    public function getNodeIdWithMinDistance(): Id
    {
        $copyNodes = $this->nodes;
        uasort(
            $copyNodes,
            function (Distance $a, Distance $b) {
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
        //TODO finish
    }


}
