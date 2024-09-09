<?php

namespace App\Provider;

use App\Configuration\DevContainerConfiguration;

class DevContainerProvider
{
    public function render(DevContainerConfiguration $config): string
    {
        return json_encode([
            'name' => '${localWorkspaceFolderBasename}',
            'dockerComposeFile' => [
                './../compose.yaml',
                './../compose.dev.yaml',
            ],
            'service' => $config->containerService,
            'workspaceFolder' => $config->containerFolder,
            'forwardPorts' => $config->forwardPorts,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
