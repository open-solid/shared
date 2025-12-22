<?php

use Doctrine\ORM\EntityManagerInterface;
use OpenSolid\Core\Application\Command\CommandBus;
use OpenSolid\Core\Application\Query\QueryBus;
use OpenSolid\Core\Domain\Event\Bus\EventBus;
use OpenSolid\Core\Infrastructure\Command\Bus\SymfonyCommandBus;
use OpenSolid\Core\Infrastructure\Event\Bus\Middleware\SymfonyEventPublisherMiddleware;
use OpenSolid\Core\Infrastructure\Event\Bus\SymfonyEventBus;
use OpenSolid\Core\Infrastructure\Query\Bus\SymfonyQueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('cqs.command.bus', SymfonyCommandBus::class)
            ->args([
                service('command.bus'),
            ])

        ->alias(CommandBus::class, 'cqs.command.bus')

        ->set('cqs.query.bus', SymfonyQueryBus::class)
            ->args([
                service('query.bus'),
            ])

        ->alias(QueryBus::class, 'cqs.query.bus')

        ->set('domain.event.bus', SymfonyEventBus::class)
            ->args([
                service('event.bus'),
            ])

        ->alias(EventBus::class, 'domain.event.bus')

        ->set('domain.event.bus.publisher.middleware', SymfonyEventPublisherMiddleware::class)
            ->args([
                service(EntityManagerInterface::class),
                service('domain.event.bus'),
            ])

        ->alias('publish_domain_events', 'domain.event.bus.publisher.middleware')
    ;
};
