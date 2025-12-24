<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer;

use OpenSolid\Bus\Envelope\Stamp\Stamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

final readonly class ChainStampTransformer implements StampTransformer
{
    /**
     * @param iterable<StampTransformer> $transformers
     */
    public function __construct(
        private iterable $transformers,
    ) {
    }

    public function supports(Stamp $stamp): bool
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($stamp)) {
                return true;
            }
        }

        return false;
    }

    public function transform(Stamp $stamp): StampInterface
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($stamp)) {
                return $transformer->transform($stamp);
            }
        }

        throw new \LogicException(\sprintf('No stamp transformer found for "%s".', $stamp::class));
    }
}
