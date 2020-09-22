<?php
declare(strict_types=1);

namespace Common\Type;

use Exception;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class QueryBus
{
    private MessageBusInterface $queryBus;


    public function __construct(MessageBusInterface $bus)
    {
        $this->queryBus = $bus;
    }

    /**
     * @param Query $query
     * @return mixed
     * @throws Exception
     */
    public function handle(Query $query)
    {
        return $this->queryBus
            ->dispatch($query)
            ->last(HandledStamp::class)
            ->getResult();
    }


}
