<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


use Common\Type\Id;
use Common\Type\ValueObject;
use RuntimeException;

class Graph extends ValueObject
{
    /**
     * @var Path[]
     */
    private array $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param Id $id
     * @return string
     */
    public function getIndexByPathId(Id $id): string
    {
        foreach ($this->paths as $pathId => $path) {
            if ($pathId === $id->getValue()) {
                return $pathId;
            }
        }

        throw new RuntimeException(sprintf('this %s does not exist in this graph', $id));
    }

    public function removePath(Id $id): void
    {
        $this->getIndexByPathId($id);
        unset($this->paths[$id->getValue()]);
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
