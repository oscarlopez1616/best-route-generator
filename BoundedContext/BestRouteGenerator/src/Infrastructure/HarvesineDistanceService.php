<?php
declare(strict_types=1);

namespace BestRouteGenerator\Infrastructure;

use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\DistanceBetween2Cities;
use BestRouteGenerator\Domain\DistanceService;

class HarvesineDistanceService implements DistanceService
{
    private const EARTH_RADIUS_IN_METERS = 6371000;

    public function findDistanceInMetersBetween2GpsPointsService(
        City $cityFrom,
        City $cityTo
    ): DistanceBetween2Cities {
        $latFrom = deg2rad($cityFrom->getCoordinate()->getLatitude());
        $lonFrom = deg2rad($cityFrom->getCoordinate()->getLongitude());
        $latTo = deg2rad($cityTo->getCoordinate()->getLatitude());
        $lonTo = deg2rad($cityTo->getCoordinate()->getLongitude());

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(
                sqrt(
                    pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
                )
            );
        $distance = $angle * self::EARTH_RADIUS_IN_METERS;

        return new DistanceBetween2Cities(
            $cityFrom,
            $cityTo,
            $distance
        );
    }
}
