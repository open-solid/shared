<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer;

use OpenSolid\Bus\Envelope\Stamp\Stamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

interface StampTransformer
{
    public function supports(Stamp $stamp): bool;

    public function transform(Stamp $stamp): StampInterface;
}
