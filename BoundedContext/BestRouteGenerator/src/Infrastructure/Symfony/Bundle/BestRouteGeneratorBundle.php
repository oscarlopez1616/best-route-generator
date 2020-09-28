<?php

declare(strict_types=1);

namespace BestRouteGenerator\Infrastructure\Symfony\Bundle;


use BestRouteGenerator\Infrastructure\Symfony\DependencyInjection\BestRouteGeneratorExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BestRouteGeneratorBundle extends Bundle
{
    protected function getContainerExtensionClass()
    {
        return BestRouteGeneratorExtension::class;
    }
}
