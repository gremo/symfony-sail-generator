<?php

namespace App\Service;

use App\Context\ContextAdapterInterface;
use App\Context\GlobalContext;
use App\Service\AbstractDatabaseService;

class DatabaseContextAdapter implements ContextAdapterInterface
{
    /**
     * @param AbstractDatabaseService $object
     */
    public function adapt(GlobalContext $context, object $object): object
    {
        return new ($object::getContextClass())(
            imageTag: $context->dbmsVersion,
        );
    }

    public function supports(object $object): bool
    {
        return $object instanceof AbstractDatabaseService;
    }
}
