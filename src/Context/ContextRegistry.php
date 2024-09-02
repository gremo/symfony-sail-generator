<?php

namespace App\Context;

use App\Exception\ContextNotFoundException;

/**
 * Context Registry
 *
 * The registry keeps active contexts in memory at a given point in time, allowing retrieval of
 * a context by type (class). Direct registration should not be performed, as it happens
 * automatically when using the context adapter.
 */
class ContextRegistry
{
    /**
     * @var array<class-string, object>
     */
    private array $contexts = [];

    /**
     * @param class-string $class
     */
    public function registerContext(string $class, object $context): void
    {
        $this->contexts[$class] = $context;
    }

    /**
     * @param class-string $contextClass
     */
    public function hasContext(string $contextClass): bool
    {
        foreach ($this->contexts as $context) {
            if ($context instanceof $contextClass) {
                return true;
            }
        }

        return false;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $contextClass
     * @return ?T
     * @throws \RuntimeException
     */
    public function getContext(string $contextClass): ?object
    {
        foreach ($this->contexts as $context) {
            if ($context instanceof $contextClass) {
                return $context;
            }
        }

        return null;
    }
}
