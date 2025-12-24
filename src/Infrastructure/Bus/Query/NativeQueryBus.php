<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Query;

use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\MessageBus;
use OpenSolid\Core\Application\Query\Bus\QueryBus;
use OpenSolid\Core\Application\Query\Message\Query;
use OpenSolid\Core\Infrastructure\Bus\Query\Error\NoHandlerForQuery;

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
