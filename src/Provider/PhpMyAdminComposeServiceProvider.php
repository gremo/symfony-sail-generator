<?php

namespace App\Provider;

use App\Configuration\PhpMyAdminServiceConfiguration;
use App\Options\ComposeTarget;

class PhpMyAdminComposeServiceProvider
{
    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(ComposeTarget $target, PhpMyAdminServiceConfiguration $config): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                'services' => [
                    "{$config->name}" => [
                        'container_name' => "\${COMPOSE_PROJECT_NAME}-{$config->name}",
                        'image' => "phpmyadmin:{$config->tag}",
                        'environment' => [
                            'APACHE_PORT' => $config->port,
                            'PMA_ARBITRARY' => true,
                            'PMA_HOST' => $config->databaseHost,
                        ],
                    ],
                ],
            ],
            ComposeTarget::Override => [
                'services' => [
                    "{$config->name}" => [
                        'ports' => [
                            "{$config->port}:{$config->port}",
                        ],
                    ]
                ],
            ],
            default => null,
        };
    }
}
