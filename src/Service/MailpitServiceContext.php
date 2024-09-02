<?php

namespace App\Service;

class MailpitServiceContext
{
    public readonly string $serviceName;
    public readonly string $containerName;
    public readonly string $imageTag;
    public readonly int $port;

    public function __construct(
        ?string $serviceName = null,
        ?string $containerName = null,
        ?string $imageTag = null,
    ) {
        $this->serviceName = $serviceName ?? 'mailpit';
        $this->containerName = $containerName ?? 'mailpit';
        $this->imageTag = $imageTag ?? 'latest';
        $this->port = 8025;
    }
}
