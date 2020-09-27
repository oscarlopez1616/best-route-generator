<?php
declare(strict_types=1);

namespace BestRouteGenerator\Infrastructure\FileSystem;


use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\Coordinate;
use Common\Domain\Exception\DomainEntityNotFoundException;
use Common\Type\Id;

class FileSystemCityRepository implements CityRepository
{
    private string $pathCityFile;

    public function __construct(string $citiesFile)
    {
        $this->pathCityFile = $citiesFile;
    }

    /**
     * @inheritDoc
     */
    public function findAllCities(): array
    {
        $cities = [];

        $file = fopen($this->pathCityFile, 'rb');
        if ($file) {
            while (($line = fgets($file)) !== false) {
                $parts = explode(" ", $line);
                $length = count($parts);
                $latitude = (float)$parts[$length - 2];
                $longitude = (float)$parts[$length - 1];
                unset($parts[$length - 1], $parts[$length - 2]);
                $cityName = implode(" ", $parts);
                $cities[] = new City(
                    new Id($cityName),
                    new Coordinate($latitude, $longitude)
                );
            }
            fclose($file);
            return $cities;
        }

        throw new DomainEntityNotFoundException(
            sprintf('Due to error opening file: %s', $this->pathCityFile)
        );
    }


}
