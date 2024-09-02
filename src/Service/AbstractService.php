<?php

namespace App\Service;

use App\Context\ContextProviderInterface;
use App\Options\ComposeTarget;
use App\Service\ServiceInterface;

abstract class AbstractService implements
    ServiceInterface,
    ContextProviderInterface
{
    #[\Override]
    public static function getContextClass(): string
    {
        $contextClass = static::class . 'Context';
        if (class_exists($contextClass)) {
            return $contextClass;
        }

        throw new \RuntimeException(sprintf(
            "The context class %s for %s was not found.",
            $contextClass,
            static::class,
        ));
    }

    public function getService(ComposeTarget $target, object $context): ?array
    {
        return null;
    }

    public function getVolumes(ComposeTarget $target, object $context): ?array
    {
        return null;
    }
}
