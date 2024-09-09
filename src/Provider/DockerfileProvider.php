<?php

namespace App\Provider;

use App\Configuration\DockerfileConfiguration;
use Twig\Environment;

class DockerfileProvider
{
    public function __construct(private Environment $twig)
    {
    }

    public function render(DockerfileConfiguration $config): string
    {
        return $this->twig->render('skeleton/Dockerfile2.twig', ['config' => $config]);
    }
}
