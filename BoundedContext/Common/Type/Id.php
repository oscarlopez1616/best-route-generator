<?php
declare(strict_types=1);

namespace Common\Type;

class Id extends ValueObject
{

    protected string $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getHash(): string
    {
        return $this->value;
    }

    private function setValue(string $value): void
    {
        $this->guard($value);

        $this->value = $value;
    }

    private function guard(string $value): void
    {

    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @param self|ValueObject $o
     *
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        return $this->getValue() === $o->getValue();
    }
}
