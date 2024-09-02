<?php

namespace App\Service;

use App\Context\ContextRegistry;
use App\Options\ComposeTarget;

class FrankenPHPService extends AbstractService
{
    public function __construct(private ContextRegistry $contextRegistry)
    {
    }

    /**
     * @param FrankenPHPServiceContext $context
     */
    #[\Override]
    public function getService(ComposeTarget $target, object $context): ?array
    {
        $database = $this->contextRegistry->getContext(DatabaseContext::class);

        return match ($target) {
            ComposeTarget::Main => [
                "{$context->serviceName}" => [
                    'container_name' => "\${COMPOSE_PROJECT_NAME}-{$context->containerName}",
                    'build' => [
                        'target' => 'frankenphp_prod',
                        'args' => [
                            'FRANKENPHP_TAG' => $context->imageTag,
                            ...($context->hasNode ? ['NODE_VERSION' => $context->nodeVersion] : [])
                        ],
                    ],
                    'volumes' => [
                        "{$context->serviceName}-config:/config",
                        "{$context->serviceName}-data:/data",
                    ],
                    'restart' => 'unless-stopped',
                ],
            ],
            ComposeTarget::Dev => [
                "{$context->serviceName}" => [
                    'build' => [
                        'target' => 'frankenphp_dev',
                    ],
                    'volumes' => [
                        'bundles:/app/public/bundles',
                        ...($context->hasNode ? ['node_modules:/app/node_modules'] : []),
                        'var:/app/var',
                        'vendor:/app/vendor',
                        ...($context->hasCron ? ['./config/docker/cron:/etc/cron.d/app'] : []),
                        './config/docker/php.dev.ini:/usr/local/etc/php/conf.d/30-php.ini',
                        './config/docker/php.ini:/usr/local/etc/php/conf.d/20-php.ini',
                        ...($context->hasSupervisor
                            ? ['./config/docker/supervisor.conf:/etc/supervisor/conf.d/app.conf']
                            : []
                        ),
                        '.:/app',
                    ],
                    'environment' => [
                        'CADDY_GLOBAL_OPTIONS' => 'debug',
                        ...($database ? ['DATABASE_URL' => $database->dns] : [])
                    ],
                ],
            ],
            ComposeTarget::Override => [
                "{$context->serviceName}" => [
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
        };
    }

    /**
     * @param FrankenPHPServiceContext $context
     */
    #[\Override]
    public function getVolumes(ComposeTarget $target, object $context): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                "{$context->serviceName}-config" => null,
                "{$context->serviceName}-data" => null,
            ],
            ComposeTarget::Dev => [
                'bundles' => null,
                ...($context->hasNode ? ['node_modules' => null] : []),
                'var' => null,
                'vendor' => null,
            ],
            default => null,
        };
    }
}
