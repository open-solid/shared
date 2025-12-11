<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Event\Bus;

use OpenSolid\Bus\FlushableMessageBus;
use OpenSolid\Bus\LazyMessageBus;
use OpenSolid\Shared\Domain\Event\Bus\EventBus;
use OpenSolid\Shared\Domain\Event\DomainEvent;

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
