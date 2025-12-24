<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Bus\Event\Middleware;

use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\FlushableMessageBus;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NextMiddleware;

final readonly class NativeEventPublisherMiddlewareBus implements Middleware
{
    public function __construct(
        private FlushableMessageBus $messageBus,
    ) {
    }

    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        $next->handle($envelope);

        $this->messageBus->flush();
    }
}
