<?php

namespace App\Renderer;

use App\Generator\GeneratorInterface;
use App\Generator\GeneratorVisitorInterface;

class DevContainerRenderer implements GeneratorVisitorInterface
{
    #[\Override]
    public static function getPriority(): int
    {
        return 8;
    }

    #[\Override]
    public function acceptGenerator(GeneratorInterface $generator): void
    {
        $generator->generateDevContainerFile($this);
    }

    public function render(DevContainerRendererContext $context): string
    {
        return json_encode([
            'name' => '${localWorkspaceFolderBasename}',
            'dockerComposeFile' => [
                './../compose.yaml',
                './../compose.dev.yaml',
            ],
            'service' => $context->containerService,
            'workspaceFolder' => $context->containerFolder,
            'forwardPorts' => $context->forwardPorts,
            ...($context->vscodeCustomizations
                ? [
                    'customizations' => [
                        'vscode' => [
                            'settings' => [
                                'files.exclude' => $context->vsCodeExcludes,
                            ],
                        ],
                    ],
                ]
                : []
            )
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
