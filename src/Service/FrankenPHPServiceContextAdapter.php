<?php

namespace App\Service;

use App\Context\ContextAdapterInterface;
use App\Context\GlobalContext;
use App\Service\FrankenPHPService;
use App\Service\FrankenPHPServiceContext;

class FrankenPHPServiceContextAdapter implements ContextAdapterInterface
{
    public function adapt(GlobalContext $context, object $object): object
    {
        return new FrankenPHPServiceContext(
            imageTag: $context->phpVersion,
            hasCron: $context->enableCron,
            hasSupervisor: $context->enableSupervisor,
            hasNode: $context->enableNode,
            nodeVersion: $context->nodeVersion,
        );
    }

    public function supports(object $object): bool
    {
        return $object instanceof FrankenPHPService;
    }
}
