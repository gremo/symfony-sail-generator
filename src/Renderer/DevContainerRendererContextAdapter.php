<?php

namespace App\Renderer;

use App\Context\ContextAdapterInterface;
use App\Context\ContextRegistry;
use App\Context\GlobalContext;
use App\Renderer\DevContainerRenderer;
use App\Renderer\DevContainerRendererContext;
use App\Service\DatabaseContext;
use App\Service\FrankenPHPServiceContext;
use App\Service\MailpitServiceContext;
use App\Service\PhpMyAdminServiceContext;

class DevContainerRendererContextAdapter implements ContextAdapterInterface
{
    public function __construct(private ContextRegistry $contextRegistry)
    {
    }

    public function adapt(GlobalContext $context, object $object): object
    {
        $frankenPHP = $this->contextRegistry->getContext(FrankenPHPServiceContext::class);
        $forwardPorts[] = "{$frankenPHP->serviceName}:80";
        $forwardPorts[] = "{$frankenPHP->serviceName}:443";

        $phpMyAdmin = $this->contextRegistry->getContext(PhpMyAdminServiceContext::class);
        if ($phpMyAdmin) {
            $forwardPorts[] = "{$phpMyAdmin->serviceName}:{$phpMyAdmin->port}";
        }

        $database = $this->contextRegistry->getContext(DatabaseContext::class);
        if ($database) {
            $forwardPorts[] = "{$database->serviceName}:{$database->port}";
        }

        $mailpit = $this->contextRegistry->getContext(MailpitServiceContext::class);
        if ($mailpit) {
            $forwardPorts[] = "{$mailpit->serviceName}:{$mailpit->port}";
        }

        return new DevContainerRendererContext(
            containerService: $frankenPHP->serviceName,
            containerFolder: '/app',
            forwardPorts: $forwardPorts,
            vscodeCustomizations: $context->enableVSCodeCustomizations,
            vsCodeExcludes: [
                ...($context->enableNode ? ['node_modules/' => true] : []),
                ...($context->assetsInstallMethod ? ['public/build/' => true] : []),
                'public/bundles/' => true,
                'var/' => true,
                'vendor/' => true,
            ],
        );
    }

    public function supports(object $object): bool
    {
        return $object instanceof DevContainerRenderer;
    }
}
