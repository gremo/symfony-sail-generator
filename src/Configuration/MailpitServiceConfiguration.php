<?php

namespace App\Configuration;

class MailpitServiceConfiguration
{
    public readonly string $name;
    public readonly string $tag;
    public readonly int $port;

    public function __construct(
        ?string $tag = null,
        ?int $port = null,
    ) {
        $this->name = 'mailpit';
        $this->tag = match ($tag) {
            null, 'current', 'latest' => 'latest',
            default => $tag,
        };
        $this->port = $port ?? 8025;
    }

    public function withTag(?string $tag): self
    {
        return new self(
            tag: $tag,
            port: $this->port,
        );
    }

    public function withPort(int $port): self
    {
        return new self(
            tag: $this->tag,
            port: $port,
        );
    }
}
