<?php

namespace App\Configuration;

class PhpMyAdminServiceConfiguration
{
    public readonly string $name;
    public readonly string $tag;
    public readonly int $port;
    public readonly string $databaseHost;

    public function __construct(
        ?string $tag = null,
        ?int $port = null,
        ?string $databaseHost = null,
    ) {
        $this->name = 'phpmyadmin';
        $this->tag = match ($tag) {
            null, 'latest', 'current' => 'latest',
            default => $tag,
        };
        $this->port = $port ?? 8080;
        $this->databaseHost = $databaseHost ?? 'db';
    }

    public function withTag(?string $tag): self
    {
        return new self(
            tag: $tag,
            port: $this->port,
        );
    }
}
