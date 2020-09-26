<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\Id;
use Common\Type\ValueObject;
use RuntimeException;

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

        throw new RuntimeException(sprintf('this %s does not exist in this path', $id));
    }

    public function removeNode(Id $id): void
    {
        $this->getIndexByNodeId($id);
        unset($this->nodes[$id->getValue()]);
    }

    public function getNodeIdWithMinimumDistance(): Id
    {
        $copyNodes = $this->nodes;
        uasort(
            $copyNodes,
            function (Distance $a, Distance $b) {
                return $a->subtractDistance($b)->getDistance();
            }

        );

        return new Id(array_keys($copyNodes)[0]);
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
