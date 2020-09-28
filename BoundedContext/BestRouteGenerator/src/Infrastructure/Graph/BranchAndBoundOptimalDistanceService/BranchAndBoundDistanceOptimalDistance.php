<?php
declare(strict_types=1);


namespace BestRouteGenerator\Infrastructure\Graph\BranchAndBoundOptimalDistanceService;


use BestRouteGenerator\Domain\City;
use BestRouteGenerator\Domain\Coordinate;
use BestRouteGenerator\Domain\DistanceService;
use BestRouteGenerator\Domain\Graph\Graph;
use BestRouteGenerator\Domain\Graph\OptimalPathService;
use BestRouteGenerator\Domain\Route;
use Common\Type\Exception\DomainException;
use Common\Type\Id;
use Exception;
use RuntimeException;


class BranchAndBoundDistanceOptimalDistance implements OptimalPathService
{
    protected int $n = 0;
    protected array $locations = [];
    protected array $costMatrix = [];
    private array $bestPath = [];
    private DistanceService $distanceService;

    /**
     * @var BranchAndBoundDistanceOptimalDistance[]  TspBranchBound instances container.
     */
    protected static array $instances = [];


    public function __construct(DistanceService $distanceService, $costMatrix = [])
    {
        if ($costMatrix) {
            $this->costMatrix = $costMatrix;
            $this->n = count($this->costMatrix);
        }
        $this->distanceService = $distanceService;
    }

    /**
     * Method to get an instance of a TspBranchBound.
     *
     * @param DistanceService $distanceService
     * @param string $name The name of the TspBranchBound.
     * @param array|null $locations An array of locations.
     *
     * @return  object  TspBranchBound instance.
     */
    public static function createInstances(
        DistanceService $distanceService,
        string $name = 'TspBranchBound',
        array $locations = null
    ) {
        $instances = &self::$instances;

        if (!isset($instances[$name])) {
            $instances[$name] = new self($distanceService);
        }

        $instances[$name]->locations = array();
        $instances[$name]->costMatrix = array();

        if ($locations && $instances[$name]->load($locations) === false) {
            throw new RuntimeException('TspBranchBound::createInstances could not load locations');
        }

        return $instances[$name];
    }

    public function load($locations)
    {
        if (empty($locations)) {
            return false;
        }

        foreach ($locations as $location) {
            if (empty($location)) {
                return false;
            }

            if ($this->addLocation($location) === false) {
                return false;
            }
        }

        return $this->loadMatrix();
    }

    private function loadMatrix(): bool
    {
        if (empty($this->locations)) {
            return false;
        }

        $this->costMatrix = array();
        $n_locations = count($this->locations);
        for ($i = 0; $i < $n_locations; $i++) {
            for ($j = 0; $j < $n_locations; $j++) {
                $distance = INF;
                if ($i !== $j) {
                    $loc1 = $this->locations[$i];
                    $loc2 = $this->locations[$j];
                    $distance = $this->distanceService->findDistanceInMetersBetween2GpsPointsService(
                        new City(
                            new Id(''),
                            new Coordinate($loc1->latitude, $loc1->longitude)
                        ),
                        new City(
                            new Id(''),
                            new Coordinate($loc2->latitude, $loc2->longitude)
                        )
                    )->getValue();
                }
                $this->costMatrix[$i][$j] = $distance;
            }
        }

        $this->n = count($this->costMatrix);

        return true;
    }

    public function addLocation($location): bool
    {
        try {
            $location = TspLocation::getInstance($location);
        } catch (Exception $e) {
            return false;
        }

        $this->locations[] = $location;

        return true;
    }

    private function rowReduction(&$reducedMatrix, &$row)
    {
        $row = array_fill(0, $this->n, INF);

        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] < $row[$i]) {
                    $row[$i] = $reducedMatrix[$i][$j];
                }
            }
        }

        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] !== INF && $row[$i] !== INF) {
                    $reducedMatrix[$i][$j] -= $row[$i];
                }
            }
        }
    }

    protected function columnReduction(&$reducedMatrix, &$col)
    {
        $col = array_fill(0, $this->n, INF);

        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] < $col[$j]) {
                    $col[$j] = $reducedMatrix[$i][$j];
                }
            }
        }

        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] !== INF && $col[$j] !== INF) {
                    $reducedMatrix[$i][$j] -= $col[$j];
                }
            }
        }
    }

    protected function calculateCost(&$reducedMatrix): float
    {
        $cost = 0;

        $row = array();
        $this->rowReduction($reducedMatrix, $row);

        $col = array();
        $this->columnReduction($reducedMatrix, $col);

        for ($i = 0; $i < $this->n; $i++) {
            $cost += ($row[$i] !== INF) ? $row[$i] : 0;
            $cost += ($col[$i] !== INF) ? $col[$i] : 0;
        }

        return $cost;
    }

    public function extractBestRoute($list): void
    {
        foreach ($list as $iValue) {
            $end = $iValue[1] + 1;
            $this->bestPath[] = $end;
        }

    }

    public function findOptimalPath(Graph $graph, Id $source): Route
    {
        $paths = $graph->getPaths();
        $path = $paths[$source->getValue()];
        unset($paths[$source->getValue()]);
        $paths = [ $source->getValue() => $path]+$paths;

        foreach ($paths as $pathId => $path) {
            $this->addLocation(
                [
                    'id' => $pathId,
                    'latitude' => $path->getCoordinate()->getLatitude(),
                    'longitude' => $path->getCoordinate()->getLongitude()
                ]
            );
        }

        if (empty($this->costMatrix) && !$this->loadMatrix()) {
            throw new DomainException('Error solving the problem costMatrix and is not possible to load it');
        }

        $costMatrix = $this->costMatrix;
        $pq = new PqTsp();
        $root = new TspNode($costMatrix, null, 0, -1, 0);
        $root->cost = $this->calculateCost($root->reducedMatrix);
        $pq->insert($root, $root->cost);


        while ($pq->valid()) {
            $min = $pq->extract();
            $pq = new PqTsp();
            $i = $min->vertex;

            if ($min->level === $this->n - 1) {
                $min->path[] = array($i, 0);
                $this->extractBestRoute($min->path);
                array_unshift($this->bestPath, end($this->bestPath));
                array_pop($this->bestPath);
                $cities = [];
                foreach ($this->bestPath as $pathId) {
                    $cities[] = new Id($this->locations[$pathId - 1]->id);
                }
                return new Route($cities);
            }

            for ($j = 0; $j < $this->n; $j++) {
                if ($min->reducedMatrix[$i][$j] !== INF) {
                    $child = new TspNode($min->reducedMatrix, $min->path, $min->level + 1, $i, $j);
                    $child->cost = $min->cost + $min->reducedMatrix[$i][$j] + $this->calculateCost($child->reducedMatrix);
                    $pq->insert($child, $child->cost);
                }
            }

            $min = null;
        }
        throw new DomainException('Unexpected Error');
    }
}



