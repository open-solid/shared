<?php

declare(strict_types=1);

namespace OpenSolid\Core\Application\Query;

use OpenSolid\Bus\Envelope\Message;

/**
 * @template T
 * @template-extends Message<T>
 */
readonly class Query extends Message
{
}
