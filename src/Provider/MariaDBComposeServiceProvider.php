<?php

namespace App\Provider;

use App\Configuration\MariaDBServiceConfiguration;
use App\Options\ComposeTarget;

class MariaDBComposeServiceProvider
{
    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(ComposeTarget $target, MariaDBServiceConfiguration $config): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                'services' => [
                    "{$config->name}" => [
                        'container_name' => "\${COMPOSE_PROJECT_NAME}-{$config->name}",
                        'image' => "mariadb:{$config->tag}",
                        'environment' => [
                            $context->envUserVar => "\${{$context->envUserVar}:-{$context->user}}",
                            $context->envPasswordVar => "\${{$context->envPasswordVar}:-{$context->password}}",
                            $context->envDatabaseVar => "\${{$context->envDatabaseVar}:-{$context->database}}",
                            'MARIADB_RANDOM_ROOT_PASSWORD' => true,
                        ],
                        'volumes' => [
                            "{$config->name}-data:/var/lib/mysql",
                        ],
                        'restart' => 'unless-stopped',
                    ],
                ],
                'volumes' => [
                    "{$config->name}-data" => null,
                ],
            ],
            default => null,
        };
    }
}
