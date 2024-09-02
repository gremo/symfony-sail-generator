<?php

namespace App\Service;

use App\Options\ComposeTarget;

class MailpitService extends AbstractService
{
    /**
     * @param MailpitServiceContext $context
     */
    #[\Override]
    public function getService(ComposeTarget $target, object $context): ?array
    {
        return match ($target) {
            ComposeTarget::Dev => [
                "{$context->serviceName}" => [
                    'container_name' => "\${COMPOSE_PROJECT_NAME}-{$context->containerName}",
                    'image' => "axllent/mailpit:{$context->imageTag}",
                    'environment' => [
                        'MP_SMTP_AUTH_ACCEPT_ANY' => true,
                        'MP_SMTP_AUTH_ALLOW_INSECURE' => true,
                    ],
                    'restart' => 'unless-stopped',
                ],
            ],
            default => null,
        };
    }
}
