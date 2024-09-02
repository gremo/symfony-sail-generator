<?php

namespace App\Generator;

use App\Context\GlobalContext;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

/**
 * Output generator facade.
 *
 * Produces the final result by utilizing the generator visitors in conjunction
 * with the global context and the specified generator type passed as an argument.
 */
class OutputGenerator
{
    /**
     * @param GeneratorVisitorInterface[] $visitors
     */
    public function __construct(
        #[AutowireLocator(GeneratorInterface::class)]
        private ContainerInterface $generators,
        #[AutowireIterator(GeneratorVisitorInterface::class, defaultPriorityMethod: 'getPriority')]
        private readonly iterable $visitors,
    ) {
    }

    /**
     * @param class-string $generator
     */
    public function getResult(GlobalContext $context, string $generator): mixed
    {
        /** @var GeneratorInterface */
        $generator = $this->generators->get($generator);
        $generator->setContext($context);

        foreach ($this->visitors as $visitor) {
            $visitor->acceptGenerator($generator);
        }

        return $generator->getResult();
    }
}
