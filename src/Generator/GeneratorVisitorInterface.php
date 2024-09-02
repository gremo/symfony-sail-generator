<?php

namespace App\Generator;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Interface for classes that are visitors of the generator.
 *
 * These classes must configure a usage priority and implement the acceptance
 * method, invoking the correct method on the generator. The result of the output
 * depends on the concrete output generator that will be used at runtime.
 */
#[AutoconfigureTag(self::class)]
interface GeneratorVisitorInterface
{
    public static function getPriority(): int;

    public function acceptGenerator(GeneratorInterface $generator): void;
}
