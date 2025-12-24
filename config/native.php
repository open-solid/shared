<?php

use Doctrine\ORM\EntityManagerInterface;
use OpenSolid\Bus\Bridge\Doctrine\Middleware\DoctrineTransactionMiddleware;
use OpenSolid\Bus\Handler\HandlersCountPolicy;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\LoggingMiddleware;
use OpenSolid\Bus\NativeLazyMessageBus;
use OpenSolid\Bus\NativeMessageBus;
use OpenSolid\Core\Application\Command\Bus\CommandBus;
use OpenSolid\Core\Application\Query\Bus\QueryBus;
use OpenSolid\Core\Domain\Event\Bus\EventBus;
use OpenSolid\Core\Infrastructure\Bus\Command\NativeCommandBus;
use OpenSolid\Core\Infrastructure\Bus\Event\Middleware\NativeEventPublisherMiddlewareBus;
use OpenSolid\Core\Infrastructure\Bus\Event\NativeEventBus;
use OpenSolid\Core\Infrastructure\Bus\Query\NativeQueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    if (interface_exists(EntityManagerInterface::class)) {
        $container->services()
            ->set('cqs.command.middleware.doctrine_transaction', DoctrineTransactionMiddleware::class)
            ->args([
                service('doctrine'),
            ])
            ->tag('cqs.command.middleware')
        ;
    }

    $container->services()
        ->set('cqs.command.middleware.logger', LoggingMiddleware::class)
            ->args([
                service('logger'),
                'command',
            ])
            ->tag('cqs.command.middleware')

        ->set('cqs.query.middleware.logger', LoggingMiddleware::class)
            ->args([
                service('logger'),
                'query',
            ])
            ->tag('cqs.query.middleware')

        ->set('cqs.command.middleware.handler', HandlingMiddleware::class)
            ->args([
                abstract_arg('cqs.command.middleware.handler.locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
                null,
                service('logger'),
                'Command',
            ])
            ->tag('cqs.command.middleware')

        ->set('cqs.command.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('cqs.command.middleware'),
            ])

        ->set('cqs.command.bus', NativeCommandBus::class)
            ->args([
                service('cqs.command.bus.native'),
            ])

        ->alias(CommandBus::class, 'cqs.command.bus')

        ->set('cqs.query.middleware.handler', HandlingMiddleware::class)
            ->args([
                abstract_arg('cqs.query.middleware.handler.locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
                null,
                service('logger'),
                'Query',
            ])
            ->tag('cqs.query.middleware')

        ->set('cqs.query.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('cqs.query.middleware'),
            ])

        ->set('cqs.query.bus', NativeQueryBus::class)
            ->args([
                service('cqs.query.bus.native'),
            ])

        ->alias(QueryBus::class, 'cqs.query.bus')

        ->set('domain.event.logger.middleware', LoggingMiddleware::class)
            ->args([
                service('logger'),
                'domain event',
            ])
            ->tag('domain.event.middleware')

        ->set('domain.event.subscriber.middleware', HandlingMiddleware::class)
            ->args([
                abstract_arg('domain.event.subscriber.locator'),
                HandlersCountPolicy::NO_HANDLER,
                null,
                service('logger'),
                'Domain event',
            ])
            ->tag('domain.event.middleware')

        ->set('domain.event.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('domain.event.middleware'),
            ])

        ->set('domain.event.bus.native.lazy', NativeLazyMessageBus::class)
            ->args([
                service('domain.event.bus.native'),
            ])

        ->set('domain.event.bus', NativeEventBus::class)
            ->args([
                service('domain.event.bus.native.lazy'),
            ])

        ->alias(EventBus::class, 'domain.event.bus')

        ->set('domain.event.bus.publisher.middleware', NativeEventPublisherMiddlewareBus::class)
            ->args([
                service('domain.event.bus'),
            ])

        ->alias('publish_domain_events', 'domain.event.bus.publisher.middleware')
    ;
};
