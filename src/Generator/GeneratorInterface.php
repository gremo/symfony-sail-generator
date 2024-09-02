<?php

namespace App\Generator;

use App\Context\GlobalContext;
use App\Renderer\ComposeRenderer;
use App\Renderer\ConfigRenderer;
use App\Renderer\DevContainerRenderer;
use App\Renderer\DockerFileRenderer;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Common interface for result generators for the application.
 *
 * Generators are responsible for generating the result based on the global context (application configuration).
 */
#[AutoconfigureTag(self::class)]
interface GeneratorInterface
{
    public function generateConfig(ConfigRenderer $renderer): void;

    public function genereComposeFile(ComposeRenderer $renderer): void;

    public function generateDockerFile(DockerFileRenderer $renderer): void;

    public function generateDevContainerFile(DevContainerRenderer $renderer): void;

    public function setContext(GlobalContext $context): void;

    public function getResult(): mixed;
}
