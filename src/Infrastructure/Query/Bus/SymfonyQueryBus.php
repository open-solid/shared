<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Query\Bus;

use OpenSolid\Shared\Application\Query\Query;
use OpenSolid\Shared\Application\Query\QueryBus;
use OpenSolid\Shared\Infrastructure\Query\Bus\Error\NoHandlerForQuery;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function ask(Query $query): mixed
    {
        try {
            return $this->handle($query);
        } catch (NoHandlerForMessageException $e) {
            throw NoHandlerForQuery::create($query, $e);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
