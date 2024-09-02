<?php

namespace App\Generator;

use App\Context\ContextAdapter;
use App\Context\GlobalContext;
use App\Options\ComposeTarget;
use App\Renderer\ComposeRenderer;
use App\Renderer\ConfigRenderer;
use App\Renderer\DevContainerRenderer;
use App\Renderer\DockerFileRenderer;

class FilesDownloadGenerator implements GeneratorInterface
{
    private GlobalContext $context;

    /**
     * @var array<string, string>
     */
    private array $files = [];

    public function __construct(
        private readonly ContextAdapter $contextAdapter,
    ) {
    }

    #[\Override]
    public function generateConfig(ConfigRenderer $renderer): void
    {
        $this->files = [
            ...$this->files,
            ...$renderer->render($this->contextAdapter->adapt($this->context, $renderer))
        ];
    }

    #[\Override]
    public function genereComposeFile(ComposeRenderer $renderer): void
    {
        $this->files['compose.yaml'] = $renderer->render(ComposeTarget::Main, $this->context);
        $this->files['compose.dev.yaml'] = $renderer->render(ComposeTarget::Dev, $this->context);
        $this->files['compose.override.yaml'] = $renderer->render(ComposeTarget::Override, $this->context);
    }

    #[\Override]
    public function generateDockerFile(DockerFileRenderer $renderer): void
    {
        $this->files['Dockerfile'] =
            $renderer->render($this->contextAdapter->adapt($this->context, $renderer));
    }

    #[\Override]
    public function generateDevContainerFile(DevContainerRenderer $renderer): void
    {
        $this->files['.devcontainer/devcontainer.json'] =
            $renderer->render($this->contextAdapter->adapt($this->context, $renderer));
    }

    #[\Override]
    public function setContext(GlobalContext $context): void
    {
        $this->context = $context;
    }

    #[\Override]
    public function getResult(): mixed
    {
        return $this->files;
    }
}
