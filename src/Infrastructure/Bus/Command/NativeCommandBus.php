<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Command;

use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\MessageBus;
use OpenSolid\Core\Application\Command\Bus\CommandBus;
use OpenSolid\Core\Application\Command\Message\Command;
use OpenSolid\Core\Infrastructure\Bus\Command\Error\NoHandlerForCommand;

readonly class NativeCommandBus implements CommandBus
{
    public function __construct(
        private MessageBus $messageBus,
    ) {
    }

    public function execute(Command $command): mixed
    {
        try {
            return $this->messageBus->dispatch($command);
        } catch (NoHandlerForMessage $e) {
            throw NoHandlerForCommand::create($command, $e);
        }
    }
}
