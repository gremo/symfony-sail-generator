<?php

namespace App\Context;

use App\Context\GlobalContext;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Interface for context adapters.
 *
 * The context adapter is responsible for adapting the global context to a target context type,
 * compatible with the object that declares itself as such through the "support" method.
 */
#[AutoconfigureTag(self::class)]
interface ContextAdapterInterface
{
    public function adapt(GlobalContext $context, object $object): object;

    public function supports(object $object): bool;
}
