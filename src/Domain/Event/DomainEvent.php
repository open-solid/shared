<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Event;

use OpenSolid\Bus\Envelope\Message;
use Symfony\Component\Uid\Uuid;

/**
 * @extends Message<void>
 */
abstract readonly class DomainEvent extends Message
{
    public string $id;
    public string $aggregateId;
    public \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId)
    {
        $this->id = Uuid::v7()::generate();
        $this->aggregateId = $aggregateId;
        $this->occurredOn = new \DateTimeImmutable();
    }
}
