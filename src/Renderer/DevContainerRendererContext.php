<?php

namespace App\Renderer;

class DevContainerRendererContext
{
    /**
     * @param string[] $forwardPorts
     * @param array<string, true> $vsCodeExcludes
     */
    public function __construct(
        public readonly string $containerService,
        public readonly string $containerFolder,
        public readonly array $forwardPorts = [],
        public readonly bool $vscodeCustomizations = true,
        public readonly array $vsCodeExcludes = [],
    ) {
    }
}
