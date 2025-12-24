<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Query;

use OpenSolid\Core\Application\Query\Bus\QueryBus;
use OpenSolid\Core\Application\Query\Message\Query;
use OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer\EnvelopeStampTrait;
use OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer\StampTransformer;
use OpenSolid\Core\Infrastructure\Bus\Query\Error\NoHandlerForQuery;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;
    use EnvelopeStampTrait;

    public function __construct(
        MessageBusInterface $queryBus,
        StampTransformer $stampTransformer,
    ) {
        $this->messageBus = $queryBus;
        $this->stampTransformer = $stampTransformer;
    }

    public function ask(Query $query): mixed
    {
        try {
            return $this->handle($this->createEnvelope($query));
        } catch (NoHandlerForMessageException $e) {
            throw NoHandlerForQuery::create($query, $e);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
