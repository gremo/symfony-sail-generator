<?php

namespace App\Configuration;

use App\Configuration\DevContainerConfiguration;
use App\Configuration\DockerfileConfiguration;
use App\Configuration\FrankenPHPServiceConfiguration;
use App\Configuration\MailpitServiceConfiguration;
use App\Configuration\PhpMyAdminServiceConfiguration;
use App\Options\AssetsBuildMethod;
use App\Options\AssetsInstallMethod;

class ConfigurationBuilder
{
    private FrankenPHPServiceConfiguration $frankenPHPConfig;
    private DockerfileConfiguration $dockerfileConfig;
    private DevContainerConfiguration $devContainerConfig;
    private ?PhpMyAdminServiceConfiguration $phpMyAdminConfig = null;
    private ?MailpitServiceConfiguration $mailpitConfig = null;

    public function __construct()
    {
        $this->frankenPHPConfig = new FrankenPHPServiceConfiguration();
        $this->dockerfileConfig = new DockerfileConfiguration();
        $this->devContainerConfig = new DevContainerConfiguration(
            containerService: $this->frankenPHPConfig->name,
            containerFolder: $this->frankenPHPConfig->rootFolder,
            forwardPorts: [
                "{$this->frankenPHPConfig->name}:80",
                "{$this->frankenPHPConfig->name}:443",
            ],
        );
    }

    public function withFrankenPHPTag(?string $frankenPHPTag): self
    {
        $this->frankenPHPConfig = $this->frankenPHPConfig->withTag($frankenPHPTag);

        return $this;
    }

    public function setEnableNode(bool $enableNode): self
    {
        $this->frankenPHPConfig = $this->frankenPHPConfig->withNode($enableNode);
        $this->dockerfileConfig = $this->dockerfileConfig->withNode($enableNode);

        return $this;
    }

    public function withNodeVersion(?string $nodeVersion): self
    {
        $this->frankenPHPConfig = $this->frankenPHPConfig->withNodeVersion($nodeVersion);

        return $this;
    }

    public function setEnableCron(bool $enableCron): self
    {
        $this->frankenPHPConfig = $this->frankenPHPConfig->withCron($enableCron);
        $this->dockerfileConfig = $this->dockerfileConfig->withCron($enableCron);

        return $this;
    }

    public function setEnableSupervisor(bool $enableSupervisor): self
    {
        $this->frankenPHPConfig = $this->frankenPHPConfig->withSupervisor($enableSupervisor);
        $this->dockerfileConfig = $this->dockerfileConfig->withSupervisor($enableSupervisor);

        return $this;
    }

    public function setEnableFrankenPHPRuntime(bool $enableFrankenPHPRuntime): self
    {
        $this->dockerfileConfig = $this->dockerfileConfig->withFrankenPHPRuntime($enableFrankenPHPRuntime);

        return $this;
    }

    public function setEnableOPcachePreload(bool $enableOPcachePreload): self
    {
        $this->dockerfileConfig = $this->dockerfileConfig->withOPcachePreload($enableOPcachePreload);

        return $this;
    }

    public function setEnablePhpMyAdmin(bool $enablePhpMyAdmin): self
    {
        if ($this->phpMyAdminConfig) {
            $this->devContainerConfig = $this->devContainerConfig
                ->withoutPortForward("{$this->phpMyAdminConfig->name}:{$this->phpMyAdminConfig->port}");
        }

        if ($enablePhpMyAdmin) {
            $this->phpMyAdminConfig ??= new PhpMyAdminServiceConfiguration();
            $this->devContainerConfig = $this->devContainerConfig
                ->withPortForward("{$this->phpMyAdminConfig->name}:{$this->phpMyAdminConfig->port}");
        } else {
            $this->phpMyAdminConfig = null;
        }

        return $this;
    }

    public function withPhpMyAdminTag(?string $tag): self
    {
        if ($this->phpMyAdminConfig) {
            $this->phpMyAdminConfig = $this->phpMyAdminConfig->withTag($tag);
        }

        return $this;
    }

    public function setEnableMailpit(bool $enableMailpit): self
    {
        if ($this->mailpitConfig) {
            $this->devContainerConfig = $this->devContainerConfig
                ->withoutPortForward("{$this->mailpitConfig->name}:{$this->mailpitConfig->port}");
        }

        if ($enableMailpit) {
            $this->mailpitConfig ??= new MailpitServiceConfiguration();
            $this->devContainerConfig = $this->devContainerConfig
                ->withPortForward("{$this->mailpitConfig->name}:{$this->mailpitConfig->port}");
        } else {
            $this->mailpitConfig = null;
        }

        return $this;
    }

    public function withMailpitPort(int $port): self
    {
        if ($this->mailpitConfig) {
            $this->devContainerConfig = $this->devContainerConfig
                ->withoutPortForward("{$this->mailpitConfig->name}:{$this->mailpitConfig->port}");
            $this->mailpitConfig = $this->mailpitConfig->withPort($port);
            $this->devContainerConfig = $this->devContainerConfig
                ->withPortForward("{$this->mailpitConfig->name}:{$this->mailpitConfig->port}");
        }

        return $this;
    }

    public function setAssetsInstallMethod(?AssetsInstallMethod $assetsInstallMethod): self
    {
        $this->dockerfileConfig = $this->dockerfileConfig->withAssetsInstallMethod($assetsInstallMethod);

        return $this;
    }

    public function setAssetsBuildMethod(?AssetsBuildMethod $assetsBuildMethod): self
    {
        $this->dockerfileConfig = $this->dockerfileConfig->withAssetsBuildMethod($assetsBuildMethod);

        return $this;
    }

    public function getFrankenPHPConfiguration(): FrankenPHPServiceConfiguration
    {
        return $this->frankenPHPConfig;
    }

    public function getPhpMyAdminConfiguration(): ?PhpMyAdminServiceConfiguration
    {
        return $this->phpMyAdminConfig;
    }

    public function getMailpitConfiguration(): ?MailpitServiceConfiguration
    {
        return $this->mailpitConfig;
    }

    public function getDockerfileConfiguration(): DockerfileConfiguration
    {
        return $this->dockerfileConfig;
    }

    public function getDevContainerConfiguration(): DevContainerConfiguration
    {
        return $this->devContainerConfig;
    }
}
