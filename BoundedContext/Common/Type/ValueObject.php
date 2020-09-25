<?php
declare(strict_types=1);

namespace Common\Type;

abstract class ValueObject
{
    public function equals(?ValueObject $o): bool
    {
        if ($o === null) {
            return $this === null;
        }
        return get_class($this) === get_class($o) && $this->equalValues($o);
    }

    abstract protected function equalValues(ValueObject $o): bool;
}
