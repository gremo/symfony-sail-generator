<?php

namespace App\Configuration;

class FrankenPHPServiceConfiguration
{
    public readonly string $name;
    public readonly string $tag;
    public readonly string $rootFolder;
    public readonly bool $enableNode;
    public readonly bool $enableCron;
    public readonly bool $enableSupervisor;
    public readonly string $nodeVersion;

    public function __construct(
        ?string $tag = null,
        ?string $nodeVersion = null,
        bool $enableNode = false,
        bool $enableCron = false,
        bool $enableSupervisor = false,
    ) {
        $this->enableNode = $enableNode;
        $this->enableCron = $enableCron;
        $this->enableSupervisor = $enableSupervisor;

        $this->name = 'frankenphp';
        $this->rootFolder = '/app';

        $this->tag = match ($tag) {
            null, 'current', 'latest' => 'latest',
            default => 'php' . ltrim($tag, 'php'),
        };

        $this->nodeVersion = match ($nodeVersion) {
            null, 'current', 'latest' => 'latest',
            default => $nodeVersion,
        };
    }

    public function withTag(?string $tag): self
    {
        return new self(
            tag: $tag,
            nodeVersion: $this->nodeVersion,
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
        );
    }

    public function withNode(bool $enable): self
    {
        return new self(
            tag: $this->tag,
            nodeVersion: $this->nodeVersion,
            enableNode: $enable,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
        );
    }

    public function withNodeVersion(?string $nodeVersion): self
    {
        return new self(
            tag: $this->tag,
            nodeVersion: $nodeVersion,
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
        );
    }

    public function withCron(bool $enable): self
    {
        return new self(
            tag: $this->tag,
            nodeVersion: $this->nodeVersion,
            enableNode: $this->enableNode,
            enableCron: $enable,
            enableSupervisor: $this->enableSupervisor,
        );
    }

    public function withSupervisor(bool $enable): self
    {
        return new self(
            tag: $this->tag,
            nodeVersion: $this->nodeVersion,
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $enable,
        );
    }
}
