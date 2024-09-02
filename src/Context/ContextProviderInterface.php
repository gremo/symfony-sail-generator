<?php

namespace App\Context;

/**
 * Interface for context providers.
 *
 * This interface allows a class to declare itself as the context for an object, which will be
 * used when the global context cannot be adapted for the object.
 */
interface ContextProviderInterface
{
    /**
     * Returns the class (as a FQCN) of the context to be used.
     *
     * @return class-string
     */
    public static function getContextClass(): string;
}
