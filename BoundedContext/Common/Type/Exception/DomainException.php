<?php
declare(strict_types=1);

namespace Common\Type\Exception;

use DomainException as SPLDomainException;

class DomainException extends SPLDomainException
{
    public function __construct(string $errorMessage)
    {
        parent::__construct($errorMessage);
    }
}
