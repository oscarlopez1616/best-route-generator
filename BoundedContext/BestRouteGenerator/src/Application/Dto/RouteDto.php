<?php
declare(strict_types=1);

namespace BestRouteGenerator\Application\Dto;


use BestRouteGenerator\Domain\Route;
use Common\Type\Id;

class RouteDto
{
    /**
     * @var string[]
     */
    private array $cities;

    /**
     * RouteDto constructor.
     * @param string[] $cities
     */
    public function __construct(array $cities)
    {
        $this->cities = $cities;
    }

    public static function assemble(Route $route): self
    {
        return new self(
            array_map(
                function (Id $cityName): string {
                    return $cityName->getValue();
                },
                $route->getCityNames()
            )
        );
    }

    public function __toString(): string
    {
        $string = '';
        foreach ($this->cities as $city) {
            $string .= $city . "\n";
        }
        return $string;
    }

}
