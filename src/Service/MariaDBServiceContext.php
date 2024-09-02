<?php

namespace App\Service;

class MariaDBServiceContext extends DatabaseContext
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
            protocol: 'mysql',
            port: $port ?? 3306,
            envUserVar: 'MARIADB_USER',
            envPasswordVar: 'MARIADB_PASSWORD',
            envDatabaseVar: 'MARIADB_DATABASE',
            envCharsetVar: 'MARIADB_CHARSET',
        );
    }
}
