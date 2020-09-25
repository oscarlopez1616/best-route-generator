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
     * @return int
     */
    public function getIndexByPathId(Id $id): int
    {
        foreach ($this->paths as $index => $path) {
            if ($path->getId()->getValue() === $id->getValue()) {
                return $index;
            }
        }

        throw new RuntimeException(sprintf('this %s does not exist in this graph', $id));
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
