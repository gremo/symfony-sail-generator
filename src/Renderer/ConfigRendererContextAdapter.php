<?php

namespace App\Renderer;

use App\Context\ContextAdapterInterface;
use App\Context\GlobalContext;

class ConfigRendererContextAdapter implements ContextAdapterInterface
{
    public function adapt(GlobalContext $context, object $object): object
    {
        return new ConfigRendererContext(
            cronFile: $context->enableCron,
            supervisorFile: $context->enableSupervisor,
        );
    }

    public function supports(object $object): bool
    {
        return $object instanceof ConfigRenderer;
    }
}
