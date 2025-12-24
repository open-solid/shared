<?php

declare(strict_types=1);

namespace OpenSolid\Core\Application\Command\Bus;

use OpenSolid\Core\Application\Command\Message\Command;

interface CommandBus
{
    /**
     * @template T
     *
     * @param Command<T> $command
     *
     * @return T
     */
    public function execute(Command $command): mixed;
}
