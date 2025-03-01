<?php
declare(strict_types=1);

namespace BestRouteGenerator\Infrastructure\Ui\Cli\Controller;

use BestRouteGenerator\Application\Dto\RouteDto;
use BestRouteGenerator\Application\Query\FindTheShortestPath\FindTheShortestPathQuery;
use Common\Type\QueryBus;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetTheShortestPathController extends Command
{
    private QueryBus $queryBus;

    public function __construct(
        QueryBus $queryBus
    ) {
        parent::__construct();
        $this->queryBus = $queryBus;
    }

    protected function configure(): void
    {
        $this
            ->setName('zinio:best-route-generator:get-shortest-path');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var RouteDto $result
         */
        $result = $this->queryBus->handle(
            new FindTheShortestPathQuery('Beijing')
        );

        $output->write($result->__toString());
        return 0;
    }

}
