<?php

namespace App\Context;

use App\Context\ContextRegistry;
use App\Context\ContextProviderInterface;
use App\Context\GlobalContext;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * Context adapter facade.
 *
 * This facade adapts the global context into the target context by iterating through all available
 * adapters until a compatible one is found for the object requesting the adaptation. It also
 * handles the creation of the default context if the object supports automatic context creation.
 */
class ContextAdapter implements ContextAdapterInterface
{
    /**
     * @param ContextAdapterInterface[] $adapters
     */
    public function __construct(
        #[AutowireIterator(ContextAdapterInterface::class, exclude: self::class)]
        private readonly iterable $adapters,
        private ContextRegistry $contextRegistry,
    ) {
    }

    public function adapt(GlobalContext $context, object $object): object
    {
        $adaptedContext = null;
        foreach ($this->adapters as $adapter) {
            if ($adapter->supports($object)) {
                $adaptedContext = $adapter->adapt($context, $object);

                break;
            }
        }

        if (!$adaptedContext && $object instanceof ContextProviderInterface) {
            $adaptedContext = new ($object::getContextClass());
        }

        if ($adaptedContext) {
            $this->contextRegistry->registerContext($object::class, $adaptedContext);

            return $adaptedContext;
        }

        throw new \RuntimeException(sprintf(
            "Unable to find a context adapter capable of handling an object of type '%s'. Either provide a " .
            "custom adapter or implement the '%s' interface to supply a default context for this object.",
            $object::class,
            ContextProviderInterface::class,
        ));
    }

    public function supports(object $object): bool
    {
        return true;
    }
}
