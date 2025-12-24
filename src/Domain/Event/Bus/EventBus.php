<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Event\Bus;

use OpenSolid\Core\Domain\Event\Message\DomainEvent;

/**
 * A message bus responsible for publishing domain events.
 */
interface EventBus
{
    public function publish(DomainEvent ...$events): void;
}
