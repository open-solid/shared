<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Event\Store;

use OpenSolid\Core\Domain\Event\DomainEvent;

trait InMemoryEventStore
{
    /**
     * @var array<class-string<DomainEvent>, DomainEvent>
     */
    private array $domainEvents = [];

    final protected function pushDomainEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[$domainEvent::class] ??= $domainEvent;
    }

    /**
     * @return array<DomainEvent>
     */
    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return array_values($domainEvents);
    }
}
