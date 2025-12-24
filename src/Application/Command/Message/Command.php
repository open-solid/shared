<?php

declare(strict_types=1);

namespace OpenSolid\Core\Application\Command\Message;

use OpenSolid\Bus\Envelope\Message;

/**
 * @template T
 * @template-extends Message<T>
 */
readonly class Command extends Message
{
}
