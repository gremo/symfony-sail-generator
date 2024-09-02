<?php

namespace App\Renderer;

class ConfigRendererContext
{
    public readonly bool $phpIniFile;
    public readonly bool $phpIniDevFile;
    public readonly bool $phpIniProdFile;

    public function __construct(
        public readonly bool $cronFile = true,
        public readonly bool $supervisorFile = true,
    ) {
        $this->phpIniFile = true;
        $this->phpIniDevFile = true;
        $this->phpIniProdFile = true;
    }
}
