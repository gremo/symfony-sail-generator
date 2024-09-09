<?php

namespace App\Configuration;

use App\Options\AssetsBuildMethod;
use App\Options\AssetsInstallMethod;

class DockerfileConfiguration
{
    public readonly bool $enableNode;
    public readonly bool $enableCron;
    public readonly bool $enableSupervisor;
    public readonly ?string $phpPdoExtension;
    public readonly bool $enableOPcachePreload;
    public readonly bool $enableFrankenPHPRuntime;
    public readonly ?AssetsInstallMethod $assetsInstallMethod;
    public readonly ?AssetsBuildMethod $assetsBuildMethod;

    public function __construct(
        bool $enableNode = false,
        bool $enableCron = false,
        bool $enableSupervisor = false,
        bool $enableOPcachePreload = false,
        bool $enableFrankenPHPRuntime = false,
        ?AssetsInstallMethod $assetsInstallMethod = null,
        ?AssetsBuildMethod $assetsBuildMethod = null,
    ) {
        $this->enableNode = $enableNode;
        $this->enableCron = $enableCron;
        $this->enableSupervisor = $enableSupervisor;
        $this->enableOPcachePreload = $enableOPcachePreload;
        $this->enableFrankenPHPRuntime = $enableFrankenPHPRuntime;
        $this->assetsInstallMethod = $assetsInstallMethod;
        $this->assetsBuildMethod = $assetsBuildMethod;
        $this->phpPdoExtension = null;
    }

    public function withNode(bool $enable): self
    {
        return new self(
            enableNode: $enable,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
            enableOPcachePreload: $this->enableOPcachePreload,
            enableFrankenPHPRuntime: $this->enableFrankenPHPRuntime,
            assetsInstallMethod: $this->assetsInstallMethod,
            assetsBuildMethod: $this->assetsBuildMethod,
        );
    }

    public function withCron(bool $enable): self
    {
        return new self(
            enableNode: $this->enableNode,
            enableCron: $enable,
            enableSupervisor: $this->enableSupervisor,
            enableOPcachePreload: $this->enableOPcachePreload,
            enableFrankenPHPRuntime: $this->enableFrankenPHPRuntime,
            assetsInstallMethod: $this->assetsInstallMethod,
            assetsBuildMethod: $this->assetsBuildMethod,
        );
    }

    public function withSupervisor(bool $enable): self
    {
        return new self(
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $enable,
            enableOPcachePreload: $this->enableOPcachePreload,
            enableFrankenPHPRuntime: $this->enableFrankenPHPRuntime,
            assetsInstallMethod: $this->assetsInstallMethod,
            assetsBuildMethod: $this->assetsBuildMethod,
        );
    }

    public function withOPcachePreload(bool $enable): self
    {
        return new self(
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
            enableOPcachePreload: $enable,
            enableFrankenPHPRuntime: $enable,
            assetsInstallMethod: $this->assetsInstallMethod,
            assetsBuildMethod: $this->assetsBuildMethod,
        );
    }

    public function withFrankenPHPRuntime(bool $enable): self
    {
        return new self(
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
            enableOPcachePreload: $this->enableOPcachePreload,
            enableFrankenPHPRuntime: $enable,
            assetsInstallMethod: $this->assetsInstallMethod,
            assetsBuildMethod: $this->assetsBuildMethod,
        );
    }

    public function withAssetsInstallMethod(?AssetsInstallMethod $assetsInstallMethod): self
    {
        return new self(
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
            enableOPcachePreload: $this->enableOPcachePreload,
            enableFrankenPHPRuntime: $this->enableFrankenPHPRuntime,
            assetsInstallMethod: $assetsInstallMethod,
            assetsBuildMethod: $this->assetsBuildMethod,
        );
    }

    public function withAssetsBuildMethod(?AssetsBuildMethod $assetsBuildMethod): self
    {
        return new self(
            enableNode: $this->enableNode,
            enableCron: $this->enableCron,
            enableSupervisor: $this->enableSupervisor,
            enableOPcachePreload: $this->enableOPcachePreload,
            enableFrankenPHPRuntime: $this->enableFrankenPHPRuntime,
            assetsInstallMethod: $this->assetsInstallMethod,
            assetsBuildMethod: $assetsBuildMethod,
        );
    }

    public function getAssetsInstallCommand(): ?string
    {
        return match ($this->assetsInstallMethod) {
            AssetsInstallMethod::Npm => 'npm ci',
            AssetsInstallMethod::Yarn1 => 'yarn install --frozen-lockfile',
            AssetsInstallMethod::Yarn2 => 'yarn install --immutable',
            default => null,
        };
    }

    public function getAssetsBuildCommand(): ?string
    {
        return match ($this->assetsBuildMethod) {
            AssetsBuildMethod::Npm => 'npm run build',
            AssetsBuildMethod::Vite => 'vite build',
            AssetsBuildMethod::WebpackEncore => 'yarn encore prod',
            AssetsBuildMethod::AssetMapper => 'bin/console asset-map:compile',
            default => null,
        };
    }
}
