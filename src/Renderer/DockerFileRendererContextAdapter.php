<?php

namespace App\Renderer;

use App\Context\ContextAdapterInterface;
use App\Context\GlobalContext;
use App\Options\DatabaseType;
use App\Renderer\DockerFileRenderer;
use App\Renderer\DockerFileRendererContext;

class DockerFileRendererContextAdapter implements ContextAdapterInterface
{
    public function adapt(GlobalContext $context, object $object): object
    {
        return new DockerFileRendererContext(
            addNode: $context->enableNode,
            addCron: $context->enableCron,
            addSupervisor: $context->enableSupervisor,
            useFrankenPHPRuntime: $context->enableFrankenPHPRuntime,
            usePHPOPcachePreload: $context->enableOPcachePreload,
            phpPdoExtension: match ($context->dbms) {
                DatabaseType::MariaDB, DatabaseType::MySQL => 'pdo_mysql',
                DatabaseType::PostgreSQL => 'pdo_pgsql',
                default => null,
            },
            assetsInstallMethod: $context->assetsInstallMethod,
            assetsBuildMethod: $context->assetsBuildMethod,
        );
    }

    public function supports(object $object): bool
    {
        return $object instanceof DockerFileRenderer;
    }
}
