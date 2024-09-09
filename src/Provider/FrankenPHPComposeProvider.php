<?php

namespace App\Provider;

use App\Configuration\FrankenPHPServiceConfiguration;
use App\Options\ComposeTarget;

class FrankenPHPComposeProvider
{
    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(ComposeTarget $target, FrankenPHPServiceConfiguration $descriptor): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                'services' => [
                    "{$descriptor->name}" => [
                    'container_name' => "\${COMPOSE_PROJECT_NAME}-{$descriptor->name}",
                    'build' => [
                        'target' => 'frankenphp_prod',
                        'args' => [
                            'FRANKENPHP_TAG' => $descriptor->tag,
                            ...($descriptor->enableNode ? ['NODE_VERSION' => $descriptor->nodeVersion] : [])
                        ],
                    ],
                    'volumes' => [
                        "{$descriptor->name}-config:/config",
                        "{$descriptor->name}-data:/data",
                    ],
                    'restart' => 'unless-stopped',
                    ],
                ],
                'volumes' => [
                    "{$descriptor->name}-config" => null,
                    "{$descriptor->name}-data" => null,
                ],
            ],
            ComposeTarget::Dev => [
                'services' => [
                    "{$descriptor->name}" => [
                    'build' => [
                        'target' => 'frankenphp_dev',
                    ],
                    'volumes' => [
                        "bundles:{$descriptor->rootFolder}/public/bundles",
                        ...($descriptor->enableNode ? ["node_modules:{$descriptor->rootFolder}/node_modules"] : []),
                        "var:{$descriptor->rootFolder}/var",
                        "vendor:{$descriptor->rootFolder}/vendor",
                        ...($descriptor->enableCron ? ['./config/docker/cron:/etc/cron.d/app'] : []),
                        './config/docker/php.dev.ini:/usr/local/etc/php/conf.d/30-php.ini',
                        './config/docker/php.ini:/usr/local/etc/php/conf.d/20-php.ini',
                        ...($descriptor->enableSupervisor
                            ? ['./config/docker/supervisor.conf:/etc/supervisor/conf.d/app.conf']
                            : []
                        ),
                        ".:{$descriptor->rootFolder}",
                    ],
                    'environment' => [
                        'CADDY_GLOBAL_OPTIONS' => 'debug',
                        /*...($database ? ['DATABASE_URL' => $database->dns] : [])*/
                    ],
                    ],
                ],
                'volumes' => [
                    'bundles' => null,
                    ...($descriptor->enableNode ? ['node_modules' => null] : []),
                    'var' => null,
                    'vendor' => null,
                ],
            ],
            ComposeTarget::Override => [
                'services' => [
                    "{$descriptor->name}" => [
                        'env_file' => [
                            ['path' => '.env.prod', 'required' => false]
                        ],
                        'ports' => [
                            '80:80',
                            '443:443',
                            '443:443/udp',
                        ],
                    ],
                ],
            ],
        };
    }
}
