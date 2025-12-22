<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Error\Store;

use OpenSolid\Core\Domain\Error\DomainError;

trait InMemoryErrorStore
{
    /**
     * @var array<DomainError>
     */
    private array $errors = [];

    final protected function pushDomainError(string|DomainError $error): void
    {
        $this->errors[] = is_string($error) ? DomainError::create($error) : $error;
    }

    final protected function throwDomainErrors(): void
    {
        if ([] === $this->errors) {
            return;
        }

        if (1 === count($this->errors)) {
            throw $this->errors[0];
        }

        $errors = [];
        foreach ($this->errors as $error) {
            $errors[$error::class] = $error::class;
        }

        if (1 === count($errors)) {
            throw $this->errors[0]::createMany($this->errors);
        }

        throw DomainError::createMany($this->errors);
    }
}
