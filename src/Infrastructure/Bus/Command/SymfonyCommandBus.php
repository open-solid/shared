<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Command;

use OpenSolid\Core\Application\Command\Bus\CommandBus;
use OpenSolid\Core\Application\Command\Message\Command;
use OpenSolid\Core\Infrastructure\Bus\Command\Error\NoHandlerForCommand;
use OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer\EnvelopeStampTrait;
use OpenSolid\Core\Infrastructure\Bus\Envelop\Stamp\Transformer\StampTransformer;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyCommandBus implements CommandBus
{
    use HandleTrait;
    use EnvelopeStampTrait;

    public function __construct(
        MessageBusInterface $commandBus,
        StampTransformer $stampTransformer,
    ) {
        $this->messageBus = $commandBus;
        $this->stampTransformer = $stampTransformer;
        $this->allowAsyncHandling = true;
    }

    public function execute(Command $command): mixed
    {
        try {
            return $this->handle($this->createEnvelope($command));
        } catch (NoHandlerForMessageException $e) {
            throw NoHandlerForCommand::create($command, $e);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
