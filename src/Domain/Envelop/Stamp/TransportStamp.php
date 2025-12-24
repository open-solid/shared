<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Envelop\Stamp;

use OpenSolid\Bus\Envelope\Stamp\Stamp;

final readonly class TransportStamp extends Stamp
{
    /**
     * @var list<string>
     */
    public array $names;

    /**
     * @param list<string>|string $names Transport names to be used for the message
     */
    public function __construct(array|string $names)
    {
        $this->names = (array) $names;
    }
}
