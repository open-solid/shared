<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Event\Bus;

use OpenSolid\Core\Domain\Event\Bus\EventBus;
use OpenSolid\Core\Domain\Event\DomainEvent;
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
