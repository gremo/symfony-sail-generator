<?php

namespace App\Renderer;

use App\Generator\GeneratorInterface;
use App\Generator\GeneratorVisitorInterface;

class ConfigRenderer implements GeneratorVisitorInterface
{
    #[\Override]
    public static function getPriority(): int
    {
        return 0;
    }

    #[\Override]
    public function acceptGenerator(GeneratorInterface $generator): void
    {
        $generator->generateConfig($this);
    }

    /**
     * @return array<string, ?string>
     */
    public function render(ConfigRendererContext $context): array
    {
        return [
            ...($context->phpIniFile ? ['config/docker/php.ini' => "; PHP shared configuration\n"] : []),
            ...($context->phpIniDevFile ? ['config/docker/php.dev.ini' => "; PHP development configuration\n"] : []),
            ...($context->phpIniProdFile ? ['config/docker/php.prod.ini' => "; PHP production configuration\n"] : []),
            ...($context->cronFile ? ['config/docker/cron' => "# App cron config file\n"] : []),
            ...($context->supervisorFile ? ['config/docker/supervisor.conf' => "; App supervisor config file\n"] : []),
        ];
    }
}
