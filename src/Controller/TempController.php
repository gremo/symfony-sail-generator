<?php

namespace App\Controller;

use App\Configuration\ConfigurationBuilder;
use App\Configuration\MariaDBServiceConfiguration;
use App\Options\ComposeTarget;
use App\Provider\DevContainerProvider;
use App\Provider\DockerfileProvider;
use App\Provider\FrankenPHPComposeProvider;
use App\Provider\MailpitComposeServiceProvider;
use App\Provider\PhpMyAdminComposeServiceProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TempController extends AbstractController
{
    #[Route('/temp', name: 'app_temp')]
    public function index(
        ConfigurationBuilder $builder,
        DevContainerProvider $devContainerProvider,
        DockerfileProvider $dockerfileProvider,
        FrankenPHPComposeProvider $frankenPhpComposeProvider,
        MailpitComposeServiceProvider $mailpitComposeServiceProvider,
        PhpMyAdminComposeServiceProvider $phpMyAdminComposeProvider,
    ): Response {

        $config = new MariaDBServiceConfiguration();

        dd($config);

        $builder
            ->withFrankenPHPTag('8.3')
            ->withNodeVersion('lts')
            ->setEnableNode(true)
            ->setEnableCron(true)
            ->setEnableSupervisor(true)
            ->setEnablePhpMyAdmin(true)
            ->setEnableMailpit(true)
        ;

        $frankenConfig = $builder->getFrankenPHPConfiguration();
        dump(
            $frankenPhpComposeProvider->getConfiguration(ComposeTarget::Main, $frankenConfig),
            $frankenPhpComposeProvider->getConfiguration(ComposeTarget::Dev, $frankenConfig),
            $frankenPhpComposeProvider->getConfiguration(ComposeTarget::Override, $frankenConfig)
        );

        dump($devContainerProvider->render($builder->getDevContainerConfiguration()));

        dump($dockerfileProvider->render($builder->getDockerfileConfiguration()));

        dump(
            $mailpitComposeServiceProvider->getConfiguration(ComposeTarget::Dev, $builder->getMailpitConfiguration()),
        );

        $phpMyAdminConfig = $builder->getPhpMyAdminConfiguration();
        dump(
            $phpMyAdminComposeProvider->getConfiguration(ComposeTarget::Main, $phpMyAdminConfig),
            $phpMyAdminComposeProvider->getConfiguration(ComposeTarget::Override, $phpMyAdminConfig),
        );

        die;
    }
}
