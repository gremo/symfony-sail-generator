<?php

namespace App\Service;

class FrankenPHPServiceContext
{
    public readonly string $serviceName;
    public readonly string $containerName;
    public readonly string $imageTag;
    public readonly string $nodeVersion;

    public function __construct(
        ?string $serviceName = null,
        ?string $containerName = null,
        ?string $imageTag = null,
        public readonly bool $hasCron = true,
        public readonly bool $hasSupervisor = true,
        public readonly bool $hasNode = true,
        ?string $nodeVersion = null,
    ) {
        $this->serviceName = $serviceName ?? 'frankenphp';
        $this->containerName = $containerName ?? 'frankenphp';
        $this->imageTag = match ($imageTag) {
            null, 'latest' => 'latest',
            default => "php{$imageTag}",
        };
        $this->nodeVersion = match ($nodeVersion) {
            null, 'latest' => 'current',
            default => $nodeVersion,
        };
    }
}
