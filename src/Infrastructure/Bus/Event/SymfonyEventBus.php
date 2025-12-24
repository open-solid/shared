<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Event;

use OpenSolid\Core\Domain\Event\Bus\EventBus;
use OpenSolid\Core\Domain\Event\Message\DomainEvent;
use OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer\EnvelopeStampTrait;
use OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer\StampTransformer;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyEventBus implements EventBus
{
    use EnvelopeStampTrait;

    public function __construct(
        private readonly MessageBusInterface $eventBus,
        StampTransformer $stampTransformer,
    ) {
        $this->stampTransformer = $stampTransformer;
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($this->createEnvelope($event));
        }
    }
}
