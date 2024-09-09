<?php

namespace App\Configuration;

use App\Options\DatabaseType;

class DatabaseServiceConfigiguration
{
    public readonly DatabaseType $type;
    public readonly string $name;
    public readonly string $tag;
    public readonly ?int $port;
    public readonly string $dns;

    public function __construct(
        DatabaseType $type,
        ?string $tag = null,
        ?int $port = null,
    ) {
        $this->type = $type;

        $this->name = 'db';

        $this->tag = match ($tag) {
            null, 'latest', 'current' => 'latest',
            default => $tag,
        };

        $this->port = $port ?? match ($type) {
            DatabaseType::MariaDB, DatabaseType::MySQL => 3306,
            DatabaseType::PostgreSQL => 5432,
        };

        $this->dns = sprintf(
            '%s://',
            match ($type) {
                DatabaseType::MariaDB, DatabaseType::MySQL => 'mysql',
                DatabaseType::PostgreSQL => 'postgresql',
            },
        );
    }

    public function withTag(?string $tag): self
    {
        return new self(
            type: $this->type,
            tag: $tag,
            port: $this->port,
        );
    }
}
