<?php

namespace App\Service;

class PostgresSQLServiceContext extends DatabaseContext
{
    public function __construct(
        ?string $serviceName = null,
        ?string $containerName = null,
        ?string $imageTag = null,
        ?int $port = null,
    ) {
        parent::__construct(
            serviceName: $serviceName ?? 'db',
            containerName: $containerName ?? 'db',
            imageTag: $imageTag ?? 'latest',
            protocol: 'postgresql',
            port: $port ?? 5432,
            envUserVar: 'POSTGRES_USER',
            envPasswordVar: 'POSTGRES_PASSWORD',
            envDatabaseVar: 'POSTGRES_DB',
            envCharsetVar: 'POSTGRES_CHARSET',
        );
    }
}
