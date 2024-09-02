<?php

namespace App\Renderer;

use App\Generator\GeneratorVisitorInterface;
use App\Generator\GeneratorInterface;
use Twig\Environment;

class DockerFileRenderer implements GeneratorVisitorInterface
{
    #[\Override]
    public static function getPriority(): int
    {
        return 0;
    }

    public function __construct(
        private Environment $twig,
        private string $template = 'skeleton/Dockerfile.twig',
    ) {
    }

    #[\Override]
    public function acceptGenerator(GeneratorInterface $generator): void
    {
        $generator->generateDockerFile($this);
    }

    public function render(DockerFileRendererContext $context): string
    {
        return $this->twig->render($this->template, ['context' => $context]);
    }
}
