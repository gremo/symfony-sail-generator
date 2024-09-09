<?php

namespace App\Provider;

use App\Configuration\MailpitServiceConfiguration;
use App\Options\ComposeTarget;

class MailpitComposeServiceProvider
{
    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(ComposeTarget $target, MailpitServiceConfiguration $config): ?array
    {
        return match ($target) {
            ComposeTarget::Dev => [
                'services' => [
                    "{$config->name}" => [
                        'container_name' => "\${COMPOSE_PROJECT_NAME}-{$config->name}",
                        'image' => "axllent/mailpit:{$config->tag}",
                        'environment' => [
                            'MP_SMTP_AUTH_ACCEPT_ANY' => true,
                            'MP_SMTP_AUTH_ALLOW_INSECURE' => true,
                        ],
                        'restart' => 'unless-stopped',
                    ],
                ]
            ],
            default => null,
        };
    }
}
