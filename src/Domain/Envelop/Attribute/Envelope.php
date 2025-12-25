<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Envelop\Attribute;

use OpenSolid\Bus\Envelope\Stamp\Stamp;

#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class Envelope
{
    /**
     * @var array<class-string<Stamp>, list<Stamp>>
     */
    public array $stamps;

    /**
     * @param list<Stamp>|Stamp $stamps
     */
    public function __construct(array|Stamp $stamps)
    {
        if ($stamps instanceof Stamp) {
            $stamps = [$stamps];
        }

        $grouped = [];
        foreach ($stamps as $stamp) {
            $grouped[$stamp::class][] = $stamp;
        }

        $this->stamps = $grouped;
    }
}
