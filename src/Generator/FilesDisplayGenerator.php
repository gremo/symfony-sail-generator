<?php

namespace App\Generator;

use App\Renderer\ConfigRenderer;

class FilesDisplayGenerator extends FilesDownloadGenerator
{
    #[\Override]
    public function generateConfig(ConfigRenderer $renderer): void
    {
    }
}
