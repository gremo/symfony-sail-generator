<?php

namespace App\Service;

class DatabaseContext
{
    public readonly string $user;

    public readonly string $password;

    public readonly string $database;

    public readonly string $charset;

    public readonly string $dns;

    public function __construct(
        public readonly string $serviceName,
        public readonly string $containerName,
        public readonly string $imageTag,
        public readonly string $protocol,
        public readonly int $port,
        public readonly string $envUserVar,
        public readonly string $envPasswordVar,
        public readonly string $envDatabaseVar,
        public readonly string $envCharsetVar,
        ?string $user = null,
        ?string $password = null,
        ?string $database = null,
        ?string $charset = null,
    ) {
        $this->user = $user ?? 'app';
        $this->password = $password ?? '!ChangeMe!';
        $this->database = $database ?? 'app';
        $this->charset = $charset ?? 'utf8';
        $this->dns = sprintf(
            '%s://${%s:-%s}:${%s:-%s}@%s:%s/${%s:-%s}?charset=${%s:-%s}',
            $protocol,
            $envUserVar,
            $this->user,
            $envPasswordVar,
            $this->password,
            $containerName,
            $port,
            $envDatabaseVar,
            $this->database,
            $envCharsetVar,
            $this->charset,
        );
    }
}
