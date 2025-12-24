<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer;

use OpenSolid\Bus\Envelope\Stamp\Stamp;
use OpenSolid\Core\Domain\Envelop\Stamp\TransportStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

final readonly class DefaultStampTransformer implements StampTransformer
{
    public function supports(Stamp $stamp): bool
    {
        return $stamp instanceof TransportStamp;
    }

    /**
     * @param TransportStamp $stamp
     */
    public function transform(Stamp $stamp): StampInterface
    {
        if (!$this->supports($stamp)) {
            throw new \LogicException(\sprintf('Stamp transformer "%s" does not support "%s".', self::class, $stamp::class));
        }

        return new TransportNamesStamp($stamp->names);
    }
}
