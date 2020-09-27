<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain\Graph;


use Common\Domain\Exception\DomainException;
use Common\Type\Id;
use Common\Type\ValueObject;
use Throwable;

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

    public function getPathByPathId(Id $id): Path
    {
        try {
            return $this->paths[$id->getValue()];
        } catch (Throwable $e) {
            throw new DomainException(sprintf('the path with id %s does not exist in this graph', $id));
        }
    }

    /**
     * @param Id $id
     * @return string
     */
    private function getIndexByPathId(Id $id): string
    {
        foreach ($this->paths as $pathId => $path) {
            if ($pathId === $id->getValue()) {
                return $pathId;
            }
        }

        throw new DomainException(sprintf('this %s does not exist in this graph', $id));
    }

    public function removePath(?Id $id): self
    {
        if ($id === null) {
            return $this;
        }
        $this->getIndexByPathId($id);
        $paths = $this->paths;
        unset($paths[$id->getValue()]);
        $pathsWithIdDrooped = [];
        foreach ($paths as $pathId => $path) {
            $pathsWithIdDrooped[$pathId] = $path->removeNode($id);
        }
        return new self($pathsWithIdDrooped);
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
