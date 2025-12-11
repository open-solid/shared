<?php

declare(strict_types=1);

namespace OpenSolid\Shared\Infrastructure\Command\Bus;

use OpenSolid\Shared\Application\Command\Command;
use OpenSolid\Shared\Application\Command\CommandBus;
use OpenSolid\Shared\Infrastructure\Command\Bus\Error\NoHandlerForCommand;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyCommandBus implements CommandBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
        $this->allowAsyncHandling = true;
    }

    public function execute(Command $command): mixed
    {
        try {
            return $this->handle($command);
        } catch (NoHandlerForMessageException $e) {
            throw NoHandlerForCommand::create($command, $e);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
