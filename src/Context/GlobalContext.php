<?php

namespace App\Context;

use App\Options\AssetsBuildMethod;
use App\Options\AssetsInstallMethod;
use App\Options\DatabaseType;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Global Application Context
 *
 * The global context represents the state of the generator and its configuration. The usage
 * of the global context should be minimized, and it should not be used directly in services,
 * but rather adapted and transformed into a specific context.
 */
class GlobalContext
{
    #[Regex('/^(latest|[0-9]+(\.[0-9]+){0,2})$/', message: 'Not a valid PHP version.')]
    public ?string $phpVersion = null;

    #[Regex('/^(lts|current|latest|[0-9]+)$/', message: 'Not a valid Node.js version.')]
    public ?string $nodeVersion = null;

    #[Regex('/^(latest|[0-9]+(\.[0-9]+){0,2})$/', message: 'Not a valid DBMS version.')]
    public ?string $dbmsVersion = null;

    public ?DatabaseType $dbms = DatabaseType::MariaDB;

    public bool $enableNode = true;

    public bool $enableCron = true;

    public bool $enableSupervisor = true;

    public bool $enableFrankenPHPRuntime = true;

    public bool $enableOPcachePreload = true;

    public ?bool $enablePhpMyAdmin = true;

    public bool $enableMailpit = true;

    public bool $enableVSCodeCustomizations = true;

    public ?AssetsInstallMethod $assetsInstallMethod = null;

    public ?AssetsBuildMethod $assetsBuildMethod = null;
}
