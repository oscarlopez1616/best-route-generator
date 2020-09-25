<?php
declare(strict_types=1);


namespace BestRouteGenerator\Domain;


interface DistanceService
{
    public function findDistanceInMetersBetween2GpsPointsService(
        City $cityFrom,
        City $cityTo
    ): DistanceBetween2Cities;
}
