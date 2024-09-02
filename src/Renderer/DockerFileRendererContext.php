<?php

namespace App\Renderer;

use App\Options\AssetsBuildMethod;
use App\Options\AssetsInstallMethod;

class DockerFileRendererContext
{
    public readonly ?string $assetsInstallCommand;

    public readonly ?string $assetsBuildCommand;

    public function __construct(
        public readonly bool $addNode,
        public readonly bool $addCron,
        public readonly bool $addSupervisor,
        public readonly bool $useFrankenPHPRuntime,
        public readonly bool $usePHPOPcachePreload,
        public readonly ?string $phpPdoExtension,
        public readonly ?AssetsInstallMethod $assetsInstallMethod,
        public readonly ?AssetsBuildMethod $assetsBuildMethod,
    ) {
        $this->assetsInstallCommand = match ($assetsInstallMethod) {
            AssetsInstallMethod::Npm => 'npm ci',
            AssetsInstallMethod::Yarn1 => 'yarn install --frozen-lockfile',
            AssetsInstallMethod::Yarn2 => 'yarn install --immutable',
            default => null,
        };

        $this->assetsBuildCommand = match ($assetsBuildMethod) {
            AssetsBuildMethod::Npm => 'npm run build',
            AssetsBuildMethod::Vite => 'vite build',
            AssetsBuildMethod::WebpackEncore => 'yarn encore prod',
            AssetsBuildMethod::AssetMapper => 'bin/console asset-map:compile',
            default => null,
        };
    }
}
