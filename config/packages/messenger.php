<?php

use OpenSolid\Core\Application\Command\Message\Command;
use OpenSolid\Core\Application\Query\Message\Query;
use OpenSolid\Core\Domain\Event\Message\DomainEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container) {
    $container->prependExtensionConfig('framework', [
        'messenger' => [
            'default_bus' => 'command.bus',
            'buses' => [
                'command.bus' => [
                    'middleware' => [
                        'router_context',
                        'doctrine_transaction',
                        'publish_domain_events',
                    ],
                ],
                'query.bus' => null,
                'event.bus' => [
                    'default_middleware' => 'allow_no_handlers',
                    'middleware' => [
                        'router_context',
                    ],
                ],
            ],
            'transports' => [
                'sync' => 'sync://',
                'async' => ['dsn' => '%env(MESSENGER_TRANSPORT_DSN)%'],
                'failed' => 'doctrine://default?queue_name=failed',
            ],
            'failure_transport' => 'failed',
            'routing' => [
                Command::class => 'sync',
                Query::class => 'sync',
                DomainEvent::class => 'async',
            ],
        ],
    ]);
};
