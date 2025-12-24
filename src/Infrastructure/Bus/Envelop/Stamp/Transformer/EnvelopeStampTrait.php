<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer;

use OpenSolid\Core\Domain\Envelop\Attribute\Envelope as EnvelopeAttribute;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\StampInterface;

trait EnvelopeStampTrait
{
    private StampTransformer $stampTransformer;

    private function createEnvelope(object $message): Envelope
    {
        $reflection = new \ReflectionClass($message);

        if (!$attributes = $reflection->getAttributes(EnvelopeAttribute::class, \ReflectionAttribute::IS_INSTANCEOF)) {
            return Envelope::wrap($message);
        }

        /** @var EnvelopeAttribute $attribute */
        $attribute = $attributes[0]->newInstance();

        // Flatten the stamps array (grouped by class) into a single list
        $domainStamps = array_merge(...array_values($attribute->stamps));

        /** @var list<StampInterface> $stamps */
        $stamps = array_map($this->stampTransformer->transform(...), $domainStamps);

        return Envelope::wrap($message, $stamps);
    }
}