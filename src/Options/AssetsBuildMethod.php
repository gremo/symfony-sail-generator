<?php

namespace App\Options;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Represents the method of compiling the assets.
 */
enum AssetsBuildMethod implements TranslatableInterface
{
    case Npm;
    case AssetMapper;
    case WebpackEncore;
    case Vite;

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return match ($this) {
            self::AssetMapper => $translator->trans('assets_build.asset_mapper', locale: $locale),
            self::Npm => $translator->trans('assets_build.npm', locale: $locale),
            self::Vite => $translator->trans('assets_build.vite', locale: $locale),
            self::WebpackEncore => $translator->trans('assets_build.encore', locale: $locale),
        };
    }
}
