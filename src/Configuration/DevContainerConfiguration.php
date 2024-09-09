<?php

namespace App\Configuration;

class DevContainerConfiguration
{
    /**
     * @param array<string> $forwardPorts
     */
    public function __construct(
        public readonly string $containerService,
        public readonly string $containerFolder,
        public readonly array $forwardPorts = [],
    ) {
    }

    public function withPortForward(string $portForward): self
    {
        return new self(
            containerService: $this->containerService,
            containerFolder: $this->containerFolder,
            forwardPorts: [...$this->forwardPorts, $portForward],
        );
    }

    public function withoutPortForward(string $portForward): self
    {
        return new self(
            containerService: $this->containerService,
            containerFolder: $this->containerFolder,
            forwardPorts: array_filter($this->forwardPorts, fn (string $f) => $f !== $portForward),
        );
    }
}
