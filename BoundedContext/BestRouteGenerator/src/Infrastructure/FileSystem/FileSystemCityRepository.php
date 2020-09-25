<?php
declare(strict_types=1);

namespace BestRouteGenerator\Infrastructure\FileSystem;


use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\CityName;
use BestRouteGenerator\Domain\CityRepository;
use BestRouteGenerator\Domain\Coordinate;
use http\Exception\RuntimeException;

class FileSystemCityRepository implements CityRepository
{
    /**
     * @inheritDoc
     */
    public function findAllCities(): array
    {
        $cities = [];

        $file = fopen("/var/www/data/BoundedContext/BestRouteGenerator/etc/data/" . "cities.txt", 'rb');
        if ($file) {
            while (($line = fgets($file)) !== false) {
                $parts = explode(" ", $line);
                $length = count($parts);
                $latitude = $parts[$length - 2];
                $longitude = $parts[$length - 1];
                unset($parts[$length - 1], $parts[$length - 2]);
                $cityName = implode(" ", $parts);
                $cities[] = new City(
                    new CityName($cityName),
                    new Coordinate((float)$latitude, (float)$longitude)
                );
            }
            fclose($file);
        } else {
            throw new RuntimeException('Error opening File');
        }

        return $cities;
    }


}
