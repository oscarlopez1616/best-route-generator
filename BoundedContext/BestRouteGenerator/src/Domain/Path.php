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
       asort($copyNodes);
       foreach ($copyNodes as $pathId => $node){
           return new CityName($pathId);
       }
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
