<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Command\Bus\Error;

use OpenSolid\Shared\Application\Command\Command;

final class NoHandlerForCommand extends \LogicException
{
    public static function create(Command $command, ?\Throwable $previous = null, int $code = 0): self
    {
        return new self(sprintf('No handler for command of type "%s".', get_class($command)), $code, $previous);
    }
}
