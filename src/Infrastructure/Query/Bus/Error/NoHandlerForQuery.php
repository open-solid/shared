<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Query\Bus\Error;

use OpenSolid\Shared\Application\Query\Query;

final class NoHandlerForQuery extends \LogicException
{
    public static function create(Query $query, ?\Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('No handler for query of type "%s".', get_class($query)), $code, $previous);
    }
}
