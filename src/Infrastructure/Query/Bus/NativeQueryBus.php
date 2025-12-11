<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Query\Bus;

use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\MessageBus;
use OpenSolid\Shared\Application\Query\Query;
use OpenSolid\Shared\Application\Query\QueryBus;
use OpenSolid\Shared\Infrastructure\Query\Bus\Error\NoHandlerForQuery;

readonly class NativeQueryBus implements QueryBus
{
    public function __construct(
        private MessageBus $messageBus,
    ) {
    }

    public function ask(Query $query): mixed
    {
        try {
            return $this->messageBus->dispatch($query);
        } catch (NoHandlerForMessage $e) {
            throw NoHandlerForQuery::create($query, $e);
        }
    }
}
