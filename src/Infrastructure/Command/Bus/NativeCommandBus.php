<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Command\Bus;

use OpenSolid\Bus\Error\NoHandlerForMessage;
use OpenSolid\Bus\MessageBus;
use OpenSolid\Shared\Application\Command\Command;
use OpenSolid\Shared\Application\Command\CommandBus;
use OpenSolid\Shared\Infrastructure\Command\Bus\Error\NoHandlerForCommand;

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
