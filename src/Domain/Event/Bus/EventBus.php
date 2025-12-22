<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Event\Bus;

use OpenSolid\Core\Domain\Event\DomainEvent;

/**
 * A message bus responsible for publishing domain events.
 */
interface EventBus
{
    public function publish(DomainEvent ...$events): void;
}
