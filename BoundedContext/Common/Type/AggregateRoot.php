<?php
declare(strict_types=1);


namespace Common\Type;


abstract class AggregateRoot
{
    abstract public function getId(): Id;
}
