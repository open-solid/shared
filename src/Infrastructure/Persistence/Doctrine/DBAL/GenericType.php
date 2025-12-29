<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Persistence\Doctrine\DBAL;

use Doctrine\DBAL\Types\Type;

/**
 * @template T
 */
abstract class GenericType extends Type
{
    /**
     * The FQCN of the concrete class to convert from/to
     *
     * @return class-string<T>
     */
    abstract public function getClass(): string;
}
