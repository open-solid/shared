<?php

declare(strict_types=1);

namespace OpenSolid\Core\Domain\Error;

class DomainError extends \DomainException
{
    /**
     * @var array<self>
     */
    public private(set) array $errors = [];

    /**
     * @param array<self> $errors
     */
    public static function createMany(array $errors): static
    {
        $messages = array_map(static fn (self $error) => $error->getMessage(), $errors);
        $messages = implode(' ', $messages);

        $self = static::create($messages);
        $self->errors = $errors;

        return $self;
    }

    public static function create(string $message = 'A domain error occurred.', int $code = 0, ?\Throwable $previous = null): static
    {
        return new static($message, $code, $previous);
    }

    final protected function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
