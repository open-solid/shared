<?php

declare(strict_types=1);

namespace OpenSolid\Core\Application\Query\Bus;

use OpenSolid\Core\Application\Query\Message\Query;

interface QueryBus
{
    /**
     * @template T
     *
     * @param Query<T> $query
     *
     * @return T
     */
    public function ask(Query $query): mixed;
}
