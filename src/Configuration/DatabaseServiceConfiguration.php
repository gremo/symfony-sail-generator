<?php

namespace App\Configuration;

class DatabaseServiceConfiguration
{
    public readonly string $name;
    public readonly string $tag;
    public readonly string $user;
    public readonly string $password;
    public readonly string $database;
    public readonly array $environment;

    public function __construct(
        ?string $name = null,
        ?string $tag = null,
        array $environment = [],
    ) {
        $this->name = $name ?? 'db';
        $this->tag = match ($tag) {
            null, 'current', 'latest' => 'latest',
            default => $tag,
        };
        $this->user = 'app';
        $this->password = '!ChangeMe!';
        $this->database = 'app';
       // $this->environment = $environment;
    }

    public function withName(?string $name): self
    {
        return new self(
            name: $name,
            tag: $this->tag,
            environment: $this->environment,
        );
    }

    public function withTag(?string $tag): self
    {
        return new self(
            name: $this->name,
            tag: $tag,
            environment: $this->environment,
        );
    }
}
