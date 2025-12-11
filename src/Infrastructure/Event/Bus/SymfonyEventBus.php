<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Event\Bus;

use OpenSolid\Shared\Domain\Event\Bus\EventBus;
use OpenSolid\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyEventBus implements EventBus
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
