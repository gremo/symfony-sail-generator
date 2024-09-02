<?php

namespace App\Options;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Represents the type (vendor) of database to be used.
 */
enum DatabaseType implements TranslatableInterface
{
    case MariaDB;
    case MySQL;
    case PostgreSQL;

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return match ($this) {
            self::MariaDB => $translator->trans('database.mariadb', locale: $locale),
            self::MySQL => $translator->trans('database.mysql', locale: $locale),
            self::PostgreSQL => $translator->trans('database.postgresql', locale: $locale),
        };
    }
}
