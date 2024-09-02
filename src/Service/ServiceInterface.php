<?php

namespace App\Service;

use App\Options\ComposeTarget;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::class)]
interface ServiceInterface
{
    /**
     * Returns the class (as a FQCN) of the context to be used.
     *
     * @return class-string
     */
    public static function getContextClass(): string;

    /**
     * @return array<string, mixed>
     */
    public function getService(ComposeTarget $target, object $context): ?array;

    /**
     * @return array<string, mixed>
     */
    public function getVolumes(ComposeTarget $target, object $context): ?array;
}
