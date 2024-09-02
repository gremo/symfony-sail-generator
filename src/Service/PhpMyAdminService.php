<?php

namespace App\Service;

use App\Options\ComposeTarget;

class PhpMyAdminService extends AbstractService
{
    /**
     * @param PhpMyAdminServiceContext $context
     */
    #[\Override]
    public function getService(ComposeTarget $target, object $context): ?array
    {
        return match ($target) {
            ComposeTarget::Main => [
                "{$context->serviceName}" => [
                    'container_name' => "\${COMPOSE_PROJECT_NAME}-{$context->containerName}",
                    'image' => "phpmyadmin:{$context->imageTag}",
                    'environment' => [
                        'APACHE_PORT' => $context->port,
                        'PMA_ARBITRARY' => true,
                        'PMA_HOST' => $context->serviceName,
                    ],
                ],
            ],
            ComposeTarget::Override => [
                "{$context->serviceName}" => [
                    'ports' => [
                        "{$context->port}:{$context->port}",
                    ],
                ]
            ],
            default => null,
        };
    }
}
