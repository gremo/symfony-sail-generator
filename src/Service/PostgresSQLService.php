<?php

namespace App\Service;

use App\Options\ComposeTarget;

class PostgresSQLService extends AbstractDatabaseService
{
    /**
     * @param PostgresSQLServiceContext $context
     */
    #[\Override]
    public function getService(ComposeTarget $target, object $context): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                "{$context->serviceName}" => [
                    'container_name' => "\${COMPOSE_PROJECT_NAME}-{$context->containerName}",
                    'image' => "postgres:{$context->imageTag}",
                    'environment' => [
                        $context->envUserVar => "\${{$context->envUserVar}:-{$context->user}}",
                        $context->envPasswordVar => "\${{$context->envPasswordVar}:-{$context->password}}",
                        $context->envDatabaseVar => "\${{$context->envDatabaseVar}:-{$context->database}}",
                    ],
                    'volumes' => [
                        "{$context->serviceName}-data:/var/lib/postgresql/data",
                    ],
                    'restart' => 'unless-stopped',
                ],
            ],
            default => null,
        };
    }

    /**
     * @param PostgresSQLServiceContext $context
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
