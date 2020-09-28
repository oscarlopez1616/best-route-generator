<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure\Graph\BranchAndBoundOptimalDistanceService;


use RuntimeException;

class TspLocation
{
    public $latitude;
    public $longitude;
    public $id;

    public function __construct($latitude, $longitude, $id = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->id = $id;
    }

    public static function getInstance($location)
    {
        $location = (array)$location;
        if (empty($location['latitude']) || empty($location['longitude'])) {
            throw new RuntimeException('TspLocation::getInstance could not load location');
        }

        // Instantiate the TspLocation.
        $id = isset($location['id']) ? $location['id'] : null;
        $tspLocation = new TspLocation($location['latitude'], $location['longitude'], $id);

        return $tspLocation;
    }
}

