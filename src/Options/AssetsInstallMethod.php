<?php

namespace App\Options;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Represents the method of installing the assets.
 */
enum AssetsInstallMethod implements TranslatableInterface
{
    case Npm;
    case Yarn2;
    case Yarn1;

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return match ($this) {
            self::Npm => $translator->trans('assets_install.npm', locale: $locale),
            self::Yarn1 => $translator->trans('assets_install.yarn1', locale: $locale),
            self::Yarn2 => $translator->trans('assets_install.yarn2', locale: $locale),
        };
    }
}
