<?php

namespace App\Service;

use App\Options\ComposeTarget;

class MySQLService extends AbstractDatabaseService
{
    /**
     * @param MySQLServiceContext $context
     */
    #[\Override]
    public function getService(ComposeTarget $target, object $context): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                "{$context->serviceName}" => [
                    'container_name' => "\${COMPOSE_PROJECT_NAME}-{$context->containerName}",
                    'image' => "mysql:{$context->imageTag}",
                    'environment' => [
                        $context->envUserVar => "\${{$context->envUserVar}:-{$context->user}}",
                        $context->envPasswordVar => "\${{$context->envPasswordVar}:-{$context->password}}",
                        $context->envDatabaseVar => "\${{$context->envDatabaseVar}:-{$context->database}}",
                        'MYSQL_RANDOM_ROOT_PASSWORD' => true,
                    ],
                    'volumes' => [
                        "{$context->serviceName}-data:/var/lib/mysql",
                    ],
                    'restart' => 'unless-stopped',
                ],
            ],
            default => null,
        };
    }

    /**
     * @param MariaDBServiceContext $context
     */
    #[\Override]
    public function getVolumes(ComposeTarget $target, object $context): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                "{$context->serviceName}-data" => null,
            ],
            default => null,
        };
    }
}
