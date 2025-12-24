<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Event;

use OpenSolid\Bus\FlushableMessageBus;
use OpenSolid\Bus\LazyMessageBus;
use OpenSolid\Core\Domain\Event\Bus\EventBus;
use OpenSolid\Core\Domain\Event\Message\DomainEvent;

final readonly class NativeEventBus implements EventBus, FlushableMessageBus
{
    public function __construct(
        private LazyMessageBus $messageBus,
    ) {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->messageBus->dispatch($event);
        }
    }

    public function flush(): void
    {
        $this->messageBus->flush();
    }
}
